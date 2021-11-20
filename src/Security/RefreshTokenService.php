<?php

namespace App\Security;

use App\Entity\RefreshToken;
use App\Entity\User;
use App\Repository\RefreshTokenRepository;
use Cake\Chronos\Chronos;
use Doctrine\ORM\EntityManagerInterface;

class RefreshTokenService
{
    private EntityManagerInterface $entityManager;
    private SecureTokenProviderInterface $secureTokenProvider;
    private RefreshTokenRepository $refreshTokenRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        SecureTokenProviderInterface $secureTokenProvider,
        RefreshTokenRepository $refreshTokenRepository
    ) {
        $this->entityManager = $entityManager;
        $this->secureTokenProvider = $secureTokenProvider;
        $this->refreshTokenRepository = $refreshTokenRepository;
    }

    public function provideToken(User $user): RefreshToken
    {
        $refreshToken = $this->refreshTokenRepository->findOneBy([
           'username' => $user->getId(),
           'type' => $user->getAccountType(),
        ]);

        $persistAndFlush = false;
        if (null === $refreshToken) {
            $refreshToken = new RefreshToken(
                $this->secureTokenProvider->provide(),
                $user->getId(),
                $user->getAccountType()->getValue(),
                Chronos::now()->addMonth()
            );

            $persistAndFlush = true;
        }

        // if token is not valid then regenerate
        if (false === $this->isValid($refreshToken)) {
            $this->revalidateToken($refreshToken);

            $persistAndFlush = true;
        }

        if ($persistAndFlush) {
            $this->entityManager->persist($refreshToken);
            $this->entityManager->flush();
        }

        return $refreshToken;
    }

    public function isValid(RefreshToken $refreshToken): bool
    {
        return Chronos::now()->lessThanOrEquals($refreshToken->getValidTill());
    }

    public function revalidateToken(RefreshToken $refreshToken): void
    {
        $refreshToken->setRefreshToken($this->secureTokenProvider->provide());
        $refreshToken->setValidTill(Chronos::now()->addMonth());
    }

    public function find(string $refreshToken): ?RefreshToken
    {
        /** @var RefreshToken|null $token */
        $token = $this->entityManager->find(RefreshToken::class, $refreshToken);

        return $token;
    }
}
