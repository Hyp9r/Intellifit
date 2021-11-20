<?php

declare(strict_types=1);

namespace App\Api\Exception;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Exception;
use MyCLabs\Enum\Enum;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class ApiException extends Exception
{
    private ?ConstraintViolationListInterface $violations = null;

    private ?string $detail = null;

    private ?array $data;

    public function __construct(string $message = '', ?int $statusCode = Response::HTTP_BAD_REQUEST, array $data = null, Throwable $previous = null)
    {
        parent::__construct($message, $statusCode ?? Response::HTTP_BAD_REQUEST, $previous);

        $this->data = $data;
    }

    public static function fromExtraAttributesException(ExtraAttributesException $extraAttributesException): self
    {
        return new self($extraAttributesException->getMessage(), Response::HTTP_BAD_REQUEST, null, $extraAttributesException);
    }

    public static function fromNotNormalizableValueException(NotNormalizableValueException $notNormalizableValueException): self
    {
        if (preg_match('/^"(?<entity>.+?)" not found: (?<value>.*?)$/', $notNormalizableValueException->getMessage(), $matches)) {
            $apiException = new self('Invalid value.', Response::HTTP_BAD_REQUEST, null, $notNormalizableValueException);

            $apiException->detail = $notNormalizableValueException->getMessage();
            $apiException->data = [
                '{{ object }}' => $matches['entity'],
                '{{ id }}' => $matches['value'],
            ];

            return $apiException;
        }

        $apiException = new self('Invalid type.', Response::HTTP_BAD_REQUEST, null, $notNormalizableValueException);

        if (preg_match('/Expected argument of type "(?<expected>.+?)", "(?<given>.*?)" given for entity "(?<entity>.+?)"\./', $notNormalizableValueException->getMessage(), $matches)) {
            $apiException->detail = $notNormalizableValueException->getMessage();
            $apiException->data = [
                '{{ object }}' => $matches['entity'],
                '{{ expected }}' => $matches['expected'],
                '{{ given }}' => $matches['given'],
            ];

            return $apiException;
        }

        if (preg_match('/^The type of the "(?<attrib>.+?)" attribute for class "(.+?)" must be one of "(?<expected>.+?)" \("(?<given>.+?)" given\).$/', $notNormalizableValueException->getMessage(), $matches) ||
            preg_match('/Expected argument of type "(?<expected>.+?)", "(?<given>.*?)" given at property path "(?<attrib>.+?)"\./', $notNormalizableValueException->getMessage(), $matches)) {
            $apiException->detail = 'Value of the attribute "{{ attrib }}" must be type of "{{ expected }}" but "{{ given }}" is given.';
            $apiException->data = [
                '{{ attrib }}' => $matches['attrib'],
                '{{ expected }}' => $matches['expected'],
                '{{ given }}' => $matches['given'],
            ];

            return $apiException;
        }

        $apiException->detail = $notNormalizableValueException->getMessage();

        return $apiException;
    }

    public static function fromUniqueConstraintViolationException(UniqueConstraintViolationException $uniqueConstraintViolationException): self
    {
        $apiException = new self('Unique violation.', Response::HTTP_BAD_REQUEST, null, $uniqueConstraintViolationException);

        if (!preg_match('/Key \((?<key>.+?)\)=\((?<value>.+?)\) already exists\./', $uniqueConstraintViolationException->getMessage(), $matches)) {
            return $apiException;
        }

        $apiException->detail = 'Key "{{ key }}" with value "{{ value }}" already exists.';
        $apiException->data = [
            '{{ key }}' => $matches['key'],
            '{{ value }}' => $matches['value'],
        ];

        return $apiException;
    }

    /**
     * @return ApiException
     */
    public static function fromNotEditableStatus(Enum $status): self
    {
        $apiException = new self('Update failed.', Response::HTTP_CONFLICT);

        $apiException->detail = 'This Entity is locked for changes due to its status "{{ status }}.';
        $apiException->data = [
            '{{ status }}' => $status->getValue(),
        ];

        return $apiException;
    }

    /**
     * @return ApiException
     */
    public static function fromNotAllowedStatus(Enum $status): self
    {
        $apiException = new self('Action failed.', Response::HTTP_CONFLICT);

        $apiException->detail = 'This Entity is locked for changes due to its status "{{ status }}".';
        $apiException->data = [
            '{{ status }}' => $status->getValue(),
        ];

        return $apiException;
    }

    public function getViolations(): ?ConstraintViolationListInterface
    {
        return $this->violations;
    }

    public function setViolations(ConstraintViolationListInterface $violations): void
    {
        $this->violations = $violations;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    /**
     * @param bool $filterBrackets Keys of data might be wrapped in brackets in order to replace placeholders in translations. This will remove them.
     */
    public function getData(bool $filterBrackets = false): ?array
    {
        if (!$filterBrackets || null === $this->data) {
            return $this->data;
        }

        $result = [];
        foreach ($this->data as $key => $item) {
            if (str_starts_with($key, '{{ ') && str_ends_with($key, ' }}')) {
                $key = substr($key, 3, -3);
            }

            $result[$key] = $item;
        }

        return $result;
    }
}
