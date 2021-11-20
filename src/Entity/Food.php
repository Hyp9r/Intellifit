<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\FoodRepository;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FoodRepository::class)
 * @ORM\Table(name="food")
 */
class Food
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"read", "list"})
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @SWG\Property(type="string", description="Name of Food.", example="Apple")
     *
     * @Groups({"read", "list", "create"})
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @SWG\Property(type="string", description="Description of the Food.", example="What fell on Newtons head?")
     *
     * @Groups({"read", "list", "create"})
     */
    private string $description;

    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     *
     *
     * @SWG\Property(type="integer", description="Protein macronutrient in grams(g)", example="20g")
     * @Groups({"read", "list", "create"})
     */
    private int $protein;

    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     *
     * @SWG\Property(type="integer", description="Fat macronutrient in grams(g)", example="20g")
     * @Groups({"read", "list", "create"})
     */
    private int $fat;

    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     *
     * @SWG\Property(type="integer", description="Carbohydrate macronutrient in grams(g)", example="20g")
     * @Groups({"read", "list", "create"})
     */
    private int $carbohydrate;

    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     *
     * @SWG\Property(type="integer", description="Amount of calories", example="220kcal")
     * @Groups({"read", "list", "create"})
     */
    private int $calories;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return int
     */
    public function getProtein(): int
    {
        return $this->protein;
    }

    /**
     * @param int $protein
     */
    public function setProtein(int $protein): void
    {
        $this->protein = $protein;
    }

    /**
     * @return int
     */
    public function getFat(): int
    {
        return $this->fat;
    }

    /**
     * @param int $fat
     */
    public function setFat(int $fat): void
    {
        $this->fat = $fat;
    }

    /**
     * @return int
     */
    public function getCarbohydrate(): int
    {
        return $this->carbohydrate;
    }

    /**
     * @param int $carbohydrate
     */
    public function setCarbohydrate(int $carbohydrate): void
    {
        $this->carbohydrate = $carbohydrate;
    }

    /**
     * @return int
     */
    public function getCalories(): int
    {
        return $this->calories;
    }

    /**
     * @param int $calories
     */
    public function setCalories(int $calories): void
    {
        $this->calories = $calories;
    }

}