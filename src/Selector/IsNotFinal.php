<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

final class IsNotFinal implements SelectorInterface
{
    public function getName(): string
    {
        return '-non final classes-';
    }

    public function matches(ClassReflection $classReflection): bool
    {
        return !$classReflection->isFinal();
    }
}
