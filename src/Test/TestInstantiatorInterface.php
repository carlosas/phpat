<?php declare(strict_types=1);

namespace PHPat\Test;

use PHPat\ShouldNotHappenException;
use PHPStan\BetterReflection\Reflection\Adapter\ReflectionClass;
use PHPStan\BetterReflection\Reflection\Adapter\ReflectionEnum;

interface TestInstantiatorInterface
{
    /**
     * Creates an instance of the given test class with proper dependency injection.
     *
     * @param  ReflectionClass|\ReflectionClass<object>|ReflectionEnum $class The test class to instantiate
     * @return object                                                  The instantiated test class with dependencies injected
     * @throws ShouldNotHappenException                                When dependencies cannot be resolved
     */
    public function instantiate(ReflectionClass|\ReflectionClass|ReflectionEnum $class): object;
}
