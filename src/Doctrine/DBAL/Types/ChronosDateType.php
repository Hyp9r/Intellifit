<?php

namespace App\Doctrine\DBAL\Types;

use Cake\Chronos\Date;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateType;

class ChronosDateType extends DateType
{
    public const CHRONOS_DATE = 'chronos_date';

    public function getName(): string
    {
        return self::CHRONOS_DATE;
    }

    /**
     * @param string|null $value
     *
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Date
    {
        if (null === $value) {
            return null;
        }

        try {
            return Date::createFromFormat('!'.$platform->getDateFormatString(), $value);
        } catch (\InvalidArgumentException $e) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), $platform->getDateFormatString());
        }
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
