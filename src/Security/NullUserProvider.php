<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @method UserInterface loadUserByIdentifier(string $identifier)
 */
class NullUserProvider implements UserProviderInterface
{
    public function loadUserByUsername($username): ?UserInterface
    {
        return null;
    }

    public function refreshUser(UserInterface $user): ?UserInterface
    {
        return null;
    }

    public function supportsClass($class): bool
    {
        return false;
    }

    public function __call(string $name, array $arguments)
    {
        // TODO: Implement @method UserInterface loadUserByIdentifier(string $identifier)
    }
}
