<?php

namespace App\Entity\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static MealType BREAKFAST()
 * @method static MealType LUNCH()
 * @method static MealType DINNER()
 * @extends Enum<string>
 *
 */
class MealType extends Enum
{
    private const BREAKFAST = 'breakfast';
    private const LUNCH = 'lunch';
    private const DINNER = 'dinner';
}