<?php
declare(strict_types=1);

namespace PHPArchiTest\Validation;

class TestErrorCollection
{
    private $values = [];

    /**
     * ErrorCollection constructor.
     * @param TestError[] $errors
     */
    public function __construct(array $errors = [])
    {
        foreach ($errors as $error) {
            $this->addValue($error);
        }
    }

    public function addValue(TestError $error): void
    {
        $this->values[] = $error;
    }

    /**
     * @return TestError[]
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
