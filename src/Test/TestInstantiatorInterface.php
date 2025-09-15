<?php declare(strict_types=1);

namespace PHPat\Test;

use PHPat\ShouldNotHappenException;

interface TestInstantiatorInterface
{
    /**
     * Creates an instance of the given test class with proper dependency injection.
     *
     * @param  \ReflectionClass<object> $class The test class to instantiate
     * @return object                   The instantiated test class with dependencies injected
     * @throws ShouldNotHappenException When dependencies cannot be resolved
     */
    public function instantiate(\ReflectionClass $class): object;
}
