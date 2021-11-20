<?php

namespace App\Doctrine\DBAL\Types;

use Cake\Chronos\Chronos;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType;

class ChronosDateTimeType extends DateTimeType
{
    public const CHRONOS_DATETIME = 'chronos_datetime';

    public function getName(): string
    {
        return self::CHRONOS_DATETIME;
    }

    /**
     * @param string|null $value
     *
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Chronos
    {
        if (null === $value) {
            return null;
        }

        try {
            return Chronos::createFromFormat($platform->getDateTimeFormatString(), $value);
        } catch (\InvalidArgumentException $e) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), $platform->getDateTimeFormatString());
        }
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
