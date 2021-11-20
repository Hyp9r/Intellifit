<?php

namespace App\Entity;

use App\Model\Security\RefreshToken as SecurityRefreshToken;
use Cake\Chronos\Chronos;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class RefreshToken
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=128, nullable=false)
     *
     * @Assert\NotBlank()
     */
    private string $refreshToken;

    /**
     * @ORM\Column(type="string", length=64, nullable=false)
     * @Assert\NotBlank()
     */
    private string $username;

    /**
     * @ORM\Column(type="string", length=16, nullable=false)
     * @Assert\NotBlank()
     */
    private string $type;

    /**
     * @ORM\Column(type="chronos_datetimetz", nullable=false)
     * @Assert\NotBlank()
     */
    private Chronos $validTill;

    public function __construct(string $refreshToken, string $username, string $type, Chronos $validTill)
    {
        $this->refreshToken = $refreshToken;
        $this->username = $username;
        $this->type = $type;
        $this->validTill = $validTill;
    }

    public function getSecurityRefreshToken(): SecurityRefreshToken
    {
        return new SecurityRefreshToken($this->refreshToken);
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(string $refreshToken): void
    {
        $this->refreshToken = $refreshToken;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getValidTill(): Chronos
    {
        return $this->validTill;
    }

    public function setValidTill(Chronos $validTill): void
    {
        $this->validTill = $validTill;
    }
}
