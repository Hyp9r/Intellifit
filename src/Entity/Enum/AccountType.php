<?php

declare(strict_types=1);

namespace App\Entity\Enum;

use App\Entity\User;
use MyCLabs\Enum\Enum;
use RuntimeException;

/**
 * @method static AccountType TRAINER()
 * @method static AccountType USER()
 * @method static AccountType MODERATOR()
 * @extends Enum<string>
 */
class AccountType extends Enum
{
    private const TRAINER = 'trainer';
    private const USER = 'user';
    private const MODERATOR = 'moderator';

    /**
     * @phpstan-return class-string
     */
    public function getClass(): string
    {
        switch ($this->getValue()) {
//            case self::TRAINER():
//                return Trainer::class;
//            case self::MODERATOR():
//                return Moderator::class;
            case self::USER():
                return User::class;
        }

        throw new RuntimeException(sprintf('Unknown class for account type "%s".', $this->getValue()));
    }
}