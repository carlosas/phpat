<?php

declare(strict_types=1);

namespace PHPat\Test;

class TestName
{
    private string $testName;
    public function __construct(string $testName)
    {
        $this->testName = $testName;
    }

    /**
     * @return string
     */
    public function getTestName(): string
    {
        return $this->testName;
    }
}
