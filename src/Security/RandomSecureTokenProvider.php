<?php

declare(strict_types=1);

namespace App\Security;

class RandomSecureTokenProvider implements SecureTokenProviderInterface
{
    public function provide(int $byteLength = 64): string
    {
        return bin2hex(random_bytes($byteLength));
    }
}
