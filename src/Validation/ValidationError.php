<?php declare(strict_types=1);

namespace PhpAT\Validation;

class ValidationError
{
    protected $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
