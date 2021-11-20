<?php

namespace App\Doctrine\DBAL\Types;

use Cake\Chronos\Chronos;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeTzType;

class ChronosDateTimeTzType extends DateTimeTzType
{
    public const CHRONOS_DATETIMETZ = 'chronos_datetimetz';

    public function getName(): string
    {
        return self::CHRONOS_DATETIMETZ;
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
            return Chronos::createFromFormat($platform->getDateTimeTzFormatString(), $value);
        } catch (\InvalidArgumentException $e) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), $platform->getDateTimeTzFormatString());
        }
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
