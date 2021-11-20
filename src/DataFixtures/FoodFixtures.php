<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Food;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FoodFixtures extends Fixture
{

    public const FIRST_FOOD = 'first';
    public const SECOND_FOOD = 'second';
    public const THIRD_FOOD = 'third';

    public function load(ObjectManager $manager)
    {
        /**
         * First
         */
        $food = new Food();
        $food->setName('Grilled chicken');
        $food->setDescription(
            'Barbecue chicken consists of chicken parts or entire chickens that are barbecued, 
            grilled or smoked.'
        );
        $food->setCarbohydrate(0);
        $food->setProtein(23);
        $food->setFat(15);
        $food->setCalories(227);

        $manager->persist($food);
        $manager->flush();

        $this->addReference(self::FIRST_FOOD, $food);

        /**
         * Second
         */
        $food = new Food();
        $food->setName('Banana');
        $food->setDescription(
            'A banana is an elongated, edible fruit – botanically a berry – 
            produced by several kinds of large herbaceous flowering plants in the genus Musa'
        );
        $food->setCarbohydrate(23);
        $food->setProtein(1);
        $food->setFat(0);
        $food->setCalories(89);

        $manager->persist($food);
        $manager->flush();

        $this->addReference(self::SECOND_FOOD, $food);

        /**
         * Third
         */
        $food = new Food();
        $food->setName('Bread');
        $food->setDescription(
            'Bread is a staple food prepared from a dough of flour and water, usually by baking.
             Throughout recorded history, it has been a prominent food in large parts of the world.'
        );
        $food->setCarbohydrate(49);
        $food->setProtein(9);
        $food->setFat(3);
        $food->setCalories(265);

        $manager->persist($food);
        $manager->flush();

        $this->addReference(self::THIRD_FOOD, $food);
    }
}