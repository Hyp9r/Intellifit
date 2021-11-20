<?php

namespace App\Model\Security;

use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

class RefreshToken
{
    /**
     * @SWG\Property(description="Refresh token.", example="9efd6555f7a8eb18e3bbc680fd25ffbfbfcbda45bceee9e3c0a2bd6ee055396a")
     *
     * @Assert\NotBlank
     *
     * @var string
     */
    private $refreshToken;

    public function __construct(string $refreshToken)
    {
        $this->refreshToken = $refreshToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
}
