<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

interface SelectorInterface
{
    /**
     * @param \ReflectionClass<object> $classReflection
     */
    /**
     * @param \ReflectionClass<object> $classReflection
     */
    /**
     * @param ClassReflection $classReflection
     */
    public function matches($classReflection): bool;

    public function getName(): string;
}
