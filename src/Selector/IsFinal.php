<?php

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;
use function trimSeparators;

class IsFinal implements SelectorInterface
{
    public function getName(): string
    {
        return '-all final classes-';
    }

    public function matches(ClassReflection $classReflection): bool
    {
        return $classReflection->isFinal();
    }
}
