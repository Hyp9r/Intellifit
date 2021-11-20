<?php

namespace App\Security;

use App\Entity\AbstractAccount;
use App\Entity\Enum\AccountType;
use App\Entity\User;
use App\Model\Security\AuthenticationTokens;
use App\Model\Security\Token;
use App\ParamConverter\Exception\NotFoundException;
use App\Repository\AdminRepository;
use App\Repository\UserRepository;
use Cake\Chronos\Chronos;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Serializer\Serializer;

class TokenGenerator
{
    use TokenTrait;

    private string $secret;
    private Serializer $serializer;
    private int $validFor;
    private RefreshTokenService $refreshTokenService;
    private UserRepository $userRepository;

    public function __construct(
        int $validFor,
        string $secret,
        Serializer $serializer,
        RefreshTokenService $refreshTokenService,
        UserRepository $userRepository
    ) {
        $this->secret = $secret;
        $this->serializer = $serializer;
        $this->validFor = $validFor;
        $this->refreshTokenService = $refreshTokenService;
        $this->userRepository = $userRepository;
    }

    public function generateAuthentication(User $user): AuthenticationTokens
    {
        $token = $this->generateToken($user);

        $refreshToken = $this->refreshTokenService->provideToken($user);

        return new AuthenticationTokens($token, $refreshToken->getSecurityRefreshToken());
    }

    public function refresh(string $refreshToken): ?Token
    {
        $token = $this->refreshTokenService->find($refreshToken);

        if (null === $token) {
            return null;
        }

        try {
            if (AccountType::USER()->getValue() === $token->getType()) {
                $user = $this->userRepository->get($token->getUsername());
            }
//            else {
//                $user = $this->adminRepository->get(Uuid::fromString($token->getUsername()));
//            }
        } catch (NotFoundException $exception) {
            return null;
        }

        if (false === $this->refreshTokenService->isValid($token)) {
            return null;
        }

        $token = $this->generateToken($user);

        return new Token($token->getToken(), $token->getExp());
    }

    private function encode(array $data): string
    {
        $json = (string) $this->serializer->encode($data, 'json');

        return $this->base64UrlEncode($json);
    }

    private function getSignature(string $header, string $payload): string
    {
        $signature = hash_hmac('sha256', $header.'.'.$payload, $this->secret, true);

        return $this->base64UrlEncode($signature);
    }

    private function generateToken(User $user): Token
    {
        $now = Chronos::now();
        $exp = $now->addSecond($this->validFor);

        $header = $this->encode(
            [
                'typ' => 'JWT',
                'alg' => 'HS256',
            ]
        );

        $payload = $this->encode(
            [
                'sub' => $user->getId(),
                'type' => $user->getAccountType(),
                'iat' => $now->getTimestamp(),
                'exp' => $exp->getTimestamp(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
            ]
        );

        $signature = $this->getSignature($header, $payload);

        $token = implode('.', [$header, $payload, $signature]);

        return new Token($token, $exp->getTimestamp());
    }
}
