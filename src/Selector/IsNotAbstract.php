<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

final class IsNotAbstract implements SelectorInterface
{
    public function getName(): string
    {
        return '-non abstract classes-';
    }

    /**
     * @param ClassReflection $classReflection
     */
    public function matches($classReflection): bool
    {
        return !$classReflection->isAbstract();
    }
}
