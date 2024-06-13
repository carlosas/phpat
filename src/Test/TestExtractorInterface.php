<?php

declare(strict_types=1);

namespace PHPat\Test;

interface TestExtractorInterface
{
    /**
     * @return iterable<\ReflectionClass>
     */
    public function __invoke(): iterable;
}