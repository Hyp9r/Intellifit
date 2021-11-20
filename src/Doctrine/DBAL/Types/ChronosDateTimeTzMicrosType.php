<?php

namespace App\Doctrine\DBAL\Types;

use Cake\Chronos\Chronos;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeTzType;

class ChronosDateTimeTzMicrosType extends DateTimeTzType
{
    public const CHRONOS_DATETIMETZ_MICROS = 'chronos_datetimetz_micros';
    private const FORMAT = 'Y-m-d H:i:s.uO';

    public function getName(): string
    {
        return self::CHRONOS_DATETIMETZ_MICROS;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $declaration = parent::getSQLDeclaration($column, $platform);

        return str_replace('(0)', '(6)', $declaration);
    }

    /**
     * @param Chronos|null $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        return $value->format(self::FORMAT);
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
            return Chronos::createFromFormat(self::FORMAT, $value);
        } catch (\InvalidArgumentException $e) {
            try {
                return Chronos::createFromFormat($platform->getDateTimeTzFormatString(), $value);
            } catch (\Exception $e) {
                throw ConversionException::conversionFailedFormat($value, $this->getName(), self::FORMAT);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
