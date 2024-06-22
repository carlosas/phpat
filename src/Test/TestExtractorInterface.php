<?php declare(strict_types=1);

namespace PHPat\Test;

interface TestExtractorInterface
{
    /**
     * @return iterable<\ReflectionClass<object>>
     */
    public function __invoke(): iterable;
}
