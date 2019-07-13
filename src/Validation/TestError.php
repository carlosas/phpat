<?php declare(strict_types=1);

namespace PHPArchiTest\Validation;

class TestError
{
    protected $message;
    protected $testName;

    public function __construct(string $testName, string $message)
    {
        $this->message = $message;
        $this->testName = $testName;
    }

    public function getTestName(): string
    {
        return $this->testName;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
