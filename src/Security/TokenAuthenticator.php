<?php

namespace App\Security;

use App\Entity\AbstractAccount;
use App\Entity\Enum\AccountType;
use App\Entity\User;
use App\Model\Error;
use Cake\Chronos\Chronos;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\Translation\TranslatorInterface;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    use TokenTrait;

    private const HEADER_AUTHORIZATION = 'Authorization';
    private const TOKEN_REGEX = '/^Bearer (?<header>[A-Za-z0-9-_]+)\.(?<payload>[A-Za-z0-9-_]+)\.(?<signature>[A-Za-z0-9-_]+)$/';

    public string $secret;
    public Serializer $serializer;
    public EntityManagerInterface $entityManager;
    private TranslatorInterface $translator;

    public function __construct(string $secret, Serializer $serializer, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $this->secret = $secret;
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    public function supports(Request $request): bool
    {
        return (bool) preg_match(self::TOKEN_REGEX, (string) $request->headers->get(static::HEADER_AUTHORIZATION, ''));
    }

    public function getCredentials(Request $request)
    {
        $header = (string) $request->headers->get(static::HEADER_AUTHORIZATION, '');

        if (!preg_match(self::TOKEN_REGEX, $header, $matches)) {
            return null;
        }

        return [
            'header' => base64_decode($matches['header']),
            'payload' => base64_decode($matches['payload']),
            'signature' => $matches['signature'],
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider): User
    {
        dd($credentials);
        if (!$this->verifySignature($credentials)) {
            throw new AuthenticationException($this->translator->trans('Incorrect credentials.', [], 'tokenAuthenticatorMessage'));
        }

        try {
            $payload = (array) $this->serializer->decode($credentials['payload'], 'json');
        } catch (NotEncodableValueException $e) {
            throw new AuthenticationException($this->translator->trans('Incorrect credentials.', [], 'tokenAuthenticatorMessage'));
        }

        if (!isset($payload['sub'], $payload['type'], $payload['exp'])) {
            throw new AuthenticationException($this->translator->trans('Incorrect credentials.', [], 'tokenAuthenticatorMessage'));
        }

        if ($this->checkExpiration($payload['exp'])) {
            throw new AuthenticationException($this->translator->trans('The token has expired.', [], 'tokenAuthenticatorMessage'));
        }

        $account = $this->getAccount($payload);

        if (false === $account instanceof User) {
            throw new AuthenticationException($this->translator->trans('Incorrect credentials.', [], 'tokenAuthenticatorMessage'));
        }

        if ($account->isDeleted()) {
            throw new AuthenticationException($this->translator->trans('Account has been deleted.', [], 'tokenAuthenticatorMessage'));
        }

        if (!$account->isActive()) {
            throw new AuthenticationException($this->translator->trans('Account has been deactivated.', [], 'tokenAuthenticatorMessage'));
        }

        return $account;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // credentials are already checked in method getUser()
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        $error = new Error($exception->getMessage());

        return new JsonResponse($error, Response::HTTP_UNAUTHORIZED);
    }

    public function start(Request $request, AuthenticationException $authException = null): JsonResponse
    {
        $error = new Error($this->translator->trans('Full authentication is required to access this resource.', [], 'tokenAuthenticatorMessage'));

        return new JsonResponse($error, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }

    private function getAccount(array $payload): ?object
    {
        $filters = $this->entityManager->getFilters();
        if ($enabled = $filters->isEnabled('deletable')) {
            $filters->disable('deletable');
        }

        $accountType = new AccountType($payload['type']);
        $account = $this->entityManager->getRepository($accountType->getClass())->find($payload['sub']);

        if ($enabled) {
            $filters->enable('deletable');
        }

        return $account;
    }

    private function verifySignature(array $data): bool
    {
        $header = $this->base64UrlEncode($data['header']);
        $payload = $this->base64UrlEncode($data['payload']);
        $signature = $this->base64UrlEncode(hash_hmac('sha256', $header.'.'.$payload, $this->secret, true));
        dd($signature, $data['signature']);
        return $signature === $data['signature'];
    }

    private function checkExpiration(int $timestamp): bool
    {
        try {
            $now = Chronos::now();
            $exp = Chronos::createFromTimestamp($timestamp);
        } catch (Exception $e) {
            return true;
        }

        return $now->greaterThan($exp);
    }
}
