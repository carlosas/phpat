<?php declare(strict_types=1);

namespace PhpAT\Validation;

class ValidationErrorCollection
{
    private $values = [];

    /**
     * ValidationErrorCollection constructor.
     * @param ValidationError[] $errors
     */
    public function __construct(array $errors = [])
    {
        foreach ($errors as $error) {
            $this->addValue($error);
        }
    }

    public function addValue(ValidationError $error): void
    {
        $this->values[] = $error;
    }

    /**
     * @return ValidationError[]
     */
    public function getValues(): array
    {
        return $this->values;
    }

    public function hasValues(): bool
    {
        return !empty($this->values);
    }
}
