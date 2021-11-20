<?php

declare(strict_types=1);

namespace App\Security;

interface SecureTokenProviderInterface
{
    public function provide(int $byteLength = 128): string;
}
