<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Enum\MealType;
use App\Entity\Meal;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MealFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /**
         * First
         */
        $meal = new Meal();
        $meal->setMealType(MealType::LUNCH());
        $meal->addItem($this->getReference(FoodFixtures::FIRST_FOOD));
        $meal->addItem($this->getReference(FoodFixtures::SECOND_FOOD));
        $meal->addItem($this->getReference(FoodFixtures::THIRD_FOOD));

        $manager->persist($meal);
        $manager->flush();
    }
}