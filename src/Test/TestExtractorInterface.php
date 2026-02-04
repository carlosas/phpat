<?php declare(strict_types=1);

namespace PHPat\Test;

interface TestExtractorInterface
{
    /**
     * @return iterable<array{0: \ReflectionClass<object>, 1: object}>
     */
    public function __invoke(): iterable;
}
