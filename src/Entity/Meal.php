<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Enum\MealType;
use App\Repository\MealRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
use http\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MealRepository::class)
 */
class Meal
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
     * @ORM\Column(type="string")
     *
     * @Groups({"create", "read", "list", "sort"})
     *
     * @Assert\NotBlank
     */
    private string $mealType;

    /**
     * @ORM\ManyToMany(targetEntity="Food")
     * @JoinTable(name="meal_food",
     *      joinColumns={@JoinColumn(name="meal_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="food_id", referencedColumnName="id", unique=true)}
     *      )
     *
     * @Groups({"create", "read"})
     *
     * @Assert\Valid
     * @Assert\Count(min=1)
     * @Assert\All({
     *     @Assert\Type(\App\Entity\Food::class)
     * })
     *
     * @var Collection<int,Food>
     */
    private Collection $items;

    /**
     * @ORM\Column(type="integer")
     *
     * @Groups({"read", "list"})
     */
    private int $calories = 0;

    public function __construct()
    {
        $this->items = new ArrayCollection();
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
    public function getMealType(): MealType
    {
        return new MealType($this->mealType);
    }

    /**
     * @param MealType $mealType
     */
    public function setMealType(MealType $mealType): void
    {
        if($mealType instanceof MealType){
            $this->mealType = $mealType->getValue();
        }else if(is_string($mealType)){
            $this->mealType = $mealType;
        }else{
            throw new InvalidArgumentException("Expected either mealtype or string", 400);
        }
    }

    /**
     * @return Collection
     */
    public function getItems(): ArrayCollection|Collection
    {
        return $this->items;
    }

    /**
     * @param Collection $items
     */
    public function setItems(ArrayCollection|Collection $items): void
    {
        $this->items = $items;
    }

    public function addItem(Food $food): void
    {
        if(!$this->items->contains($food)){
            $this->items->add($food);
            $this->increaseCalories($food->getCalories());
        }
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

    public function increaseCalories(int $calories): void
    {
        $this->calories += $calories;
    }

}