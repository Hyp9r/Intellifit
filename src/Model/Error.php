<?php

declare(strict_types=1);

namespace App\Model;

use OpenApi\Annotations as SWG;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class Error
{
    /**
     * @SWG\Property(description="General description of issue.", example="Invalid JSON data.")
     */
    private string $message;

    /**
     * @SWG\Property(description="General description of issue.", example="Invalid JSON data.")
     */
    private ?string $messageTemplate;

    /**
     * @SWG\Property(description="More detailed information.")
     */
    private ?string $detail = null;

    /**
     * @SWG\Property(type="object", description="Some issues can provide additional 'key':'value' data.")`.
     */
    private ?array $data = null;

    /**
     * @SWG\Property(
     *     property="violations",
     *     type="array",
     *     description="Validation errors contains this list with details.",
     *     @SWG\Items(
     *         type="object",
     *         @SWG\Property(type="string", property="message", description="Error title (e.g. *This value is not valid*).", example="This value should not be blank."),
     *         @SWG\Property(type="string", property="propertyPath", description="Path of field (e.g. *items[0].productCode*).", example="firstName"),
     *         @SWG\Property(type="string", property="code", description="Error code.", example="3b70573f-c259-4df7-89fd-ad0104c1b828"),
     *         @SWG\Property(type="object", property="parameters", description="Internal data regarding to current violation.")
     *     )
     * )
     */
    private ?ConstraintViolationListInterface $violations = null;

    public function __construct(string $message, ?string $messageTemplate = null)
    {
        $this->message = $message;
        $this->messageTemplate = $messageTemplate;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getMessageTemplate(): ?string
    {
        return $this->messageTemplate;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(?string $detail): void
    {
        $this->detail = $detail;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(?array $data): void
    {
        $this->data = $data;
    }

    public function getViolations(): ?ConstraintViolationListInterface
    {
        return $this->violations;
    }

    public function setViolations(ConstraintViolationListInterface $violations): void
    {
        $this->violations = $violations;
    }
}
