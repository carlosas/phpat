<?php

declare(strict_types=1);

namespace PHPat\Test;

use PHPat\Configuration;
use PHPStan\Reflection\ClassReflection;

class TestExtractor
{
    private Configuration $configuration;

    public function __construct(
        Configuration $configuration
    ) {
        $this->configuration = $configuration;
    }

    /**
     * @return iterable<ClassReflection>
     */
    public function __invoke(): iterable
    {
        foreach ($this->configuration->getTests() as $test) {
            yield $test;
        }
    }
}
