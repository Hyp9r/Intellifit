<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Enum\AccountType;
use Cake\Chronos\Chronos;
use Cake\Chronos\Date;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Annotations as SWG;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @UniqueEntity(fields={"phone"})
 * @UniqueEntity(fields={"email"})
 * @method string getUserIdentifier()
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface, AccountInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"read", "list", "info"})
     */
    protected int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @SWG\Property(example="John")
     *
     * @Groups({"read", "info", "create", "update", "list", "register"})
     *
     * @Assert\Length(min=2, max=255, allowEmptyString=false)
     * @Assert\NotBlank
     */
    protected string $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @SWG\Property(example="John")
     *
     * @Groups({"read", "info", "create", "update", "list", "register"})
     *
     * @Assert\Length(min=2, max=255, allowEmptyString=false)
     * @Assert\NotBlank
     */
    protected string $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @SWG\Property(example="john.doe@example.com")
     *
     * @Groups({"read", "info", "create", "update", "list", "register"})
     *
     * @Assert\Email
     * @Assert\NotBlank
     */
    protected string $email;

    /**
     * @ORM\Column(type="string")
     *
     * @Groups({"create"})
     *
     * @Assert\NotBlank
     */
    protected string $password;

    /**
     * @ORM\Column(type="chronos_datetime")
     * @SWG\Property(example="2020-05-25T13:36:05+00:00")
     *
     * @Groups({"read", "list", "sort"})
     */
    protected Chronos $createdAt;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups({"create", "read", "update", "list", "register"})
     *
     * @Assert\NotNull
     */
    protected bool $active;
    /**
     * @ORM\Column(type="string", length=35, nullable=true)
     * @SWG\Property(example="+385 123456789")
     *
     * @Groups({"create", "read", "info", "list", "register"})
     *
     * @Assert\NotBlank(groups={"User"})
     * @Assert\Length(min="2", max="35", allowEmptyString=false)
     */
    protected ?string $phone = null;

    /**
     * @ORM\Column(type="chronos_datetime", nullable=true)
     */
    private ?Chronos $tokenCreatedAt = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $token = null;

    /**
     * @ORM\Column(type="chronos_date")
     * @SWG\Property(example="1970-07-12")
     *
     * @Groups({"create", "read", "update", "list", "register"})
     *
     * @Assert\NotNull
     * @Assert\LessThanOrEqual("-18 years")
     */
    private Date $dateOfBirth;

    /**
     * @ORM\Column(type="string")
     * @SWG\Property(example="male")
     *
     * @Groups({"create", "read", "update", "list", "register"})
     *
     * @Assert\NotNull
     * @Assert\Choice({"male", "female"})
     */
    private string $sex;

    /**
     * @ORM\Column(type="integer")
     * @SWG\Property(example="80", description="Weight in kg.")
     *
     * @Groups({"create", "read", "update", "list", "register"})
     *
     * @Assert\NotNull
     */
    private int $weight;

    /**
     * @ORM\Column(type="integer")
     * @SWG\Property(example="180", description="Height in cm.")
     *
     * @Groups({"create", "read", "update", "list", "register"})
     *
     * @Assert\NotNull
     */
    private int $height;

    public function __construct()
    {
        $this->createdAt = Chronos::now();
        $this->active = true;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = strtolower($email);
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return Chronos|\Cake\Chronos\ChronosInterface
     */
    public function getCreatedAt(): \Cake\Chronos\ChronosInterface|Chronos
    {
        return $this->createdAt;
    }

    /**
     * @param Chronos|\Cake\Chronos\ChronosInterface $createdAt
     */
    public function setCreatedAt(\Cake\Chronos\ChronosInterface|Chronos $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     */
    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getRoles(): array
    {
        return [
            'ROLE_USER',
        ];
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUsername()
    {
        return $this->getId();
    }

    public function __call(string $name, array $arguments)
    {
        // TODO: Implement @method string getUserIdentifier()
    }

    /**
     * @return Chronos|null
     */
    public function getTokenCreatedAt(): ?Chronos
    {
        return $this->tokenCreatedAt;
    }

    /**
     * @param Chronos|null $tokenCreatedAt
     */
    public function setTokenCreatedAt(?Chronos $tokenCreatedAt): void
    {
        $this->tokenCreatedAt = $tokenCreatedAt;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string|null $token
     */
    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return Date
     */
    public function getDateOfBirth(): Date
    {
        return $this->dateOfBirth;
    }

    /**
     * @param Date $dateOfBirth
     */
    public function setDateOfBirth(Date $dateOfBirth): void
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * @return string
     */
    public function getSex(): string
    {
        return $this->sex;
    }

    /**
     * @param string $sex
     */
    public function setSex(string $sex): void
    {
        $this->sex = $sex;
    }

    /**
     * @return int
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    /**
     * @param int $weight
     */
    public function setWeight(int $weight): void
    {
        $this->weight = $weight;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight(int $height): void
    {
        $this->height = $height;
    }

    public function getAccountType(): AccountType
    {
        return AccountType::USER();
    }

    public function isTokenValid(): bool
    {
        if (null === $this->token || null === $this->tokenCreatedAt) {
            return false;
        }

        // token is valid for 1 week
        return Chronos::now()->subWeek()->lessThan($this->tokenCreatedAt);
    }

    public function resetToken(): void
    {
        $this->token = null;
        $this->tokenCreatedAt = null;
    }
}