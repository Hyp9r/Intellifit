<?php

namespace App\Model\Security;

use OpenApi\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

class UserLogin
{
    /**
     * @SWG\Property(description="Users email address or phone number.", example="john.doe@example.com")
     *
     * @Assert\NotBlank
     *
     * @var string
     */
    private $login;

    /**
     * @SWG\Property(example="A1B2C3D4")
     *
     * @Assert\NotBlank
     *
     * @var string
     */
    private $password;

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = strtolower($login);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
