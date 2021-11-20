<?php

namespace App\Model\Security;

use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

class AuthenticationTokens
{
    /**
     * @SWG\Property(description="Long live token.", example="9efd6555f7a8eb18e3bbc680fd25ffbfbfcbda45bceee9e3c0a2bd6ee055396a")
     *
     * @Groups({"read"})
     */
    private string $refreshToken;

    /**
     * @SWG\Property(description="Serialized JSON web token.", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjEyMzQ1Njc4LWFiY2QtMTIzNC1hYmNkLTEyMzM0NTY3ODlhYiIsInR5cGUiOiJhZG1pbiIsImV4cGlyZSI6MTU5MzgyODIyMn0.PlscuLItHb4oRvHBiLO5k1o-O1YzdExp0nlcmmZvQ3k")
     *
     * @Groups({"read"})
     */
    private string $token;

    /**
     * @SWG\Property(description="Unix timestamp representing the date and time of token expiration.", example=1590922425)
     *
     * @Groups({"read"})
     */
    private int $exp;

    public function __construct(Token $token, RefreshToken $refreshToken)
    {
        $this->token = $token->getToken();
        $this->exp = $token->getExp();
        $this->refreshToken = $refreshToken->getRefreshToken();
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getExp(): int
    {
        return $this->exp;
    }
}
