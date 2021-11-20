<?php

declare(strict_types=1);

namespace App\Entity\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static MacronutrientGramToCalorieValue PROTEIN()
 * @method static MacronutrientGramToCalorieValue CARBOHYDRATE()
 * @method static MacronutrientGramToCalorieValue FAT()
 * @extends Enum<integer>
 * */
class MacronutrientGramToCalorieValue extends Enum
{
    private const PROTEIN = 4;
    private const CARBOHYDRATE = 4;
    private const FAT = 9;
}