<?php

namespace App\Model\Security;

use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

class Token
{
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

    public function __construct(string $token, int $exp)
    {
        $this->token = $token;
        $this->exp = $exp;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getExp(): int
    {
        return $this->exp;
    }

    public function setExp(int $exp): void
    {
        $this->exp = $exp;
    }
}
