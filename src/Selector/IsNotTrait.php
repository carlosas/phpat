<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

final class IsNotTrait implements SelectorInterface
{
    public function getName(): string
    {
        return '-non trait classes-';
    }

    public function matches(ClassReflection $classReflection): bool
    {
        return !$classReflection->isTrait();
    }
}
