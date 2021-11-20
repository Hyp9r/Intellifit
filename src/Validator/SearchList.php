<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class SearchList extends Constraint
{
    public const SELECT = '6d5a8671-9962-49be-8f80-3c630fd91334';

    /**
     * @var array<string,string>
     */
    protected static $errorNames = [
        self::SELECT => 'SELECT',
    ];

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
