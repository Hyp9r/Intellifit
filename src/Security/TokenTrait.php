<?php

declare(strict_types=1);

namespace App\Security;

trait TokenTrait
{
    private function base64UrlEncode(string $input): string
    {
        return str_replace(
            ['+', '/', '='],
            ['-', '_', ''],
            base64_encode($input)
        );
    }
}