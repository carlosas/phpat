<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

final class IsNotFinal implements SelectorInterface
{
    public function getName(): string
    {
        return '-non final classes-';
    }

    /**
     * @param \ReflectionClass<object> $classReflection
     */
    /**
     * @param ClassReflection $classReflection
     */
    public function matches($classReflection): bool
    {
        return !$classReflection->isFinal();
    }
}
