<?php

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;
use function trimSeparators;

class IsAbstract implements SelectorInterface
{
    public function getName(): string
    {
        return '-abstract classes-';
    }

    public function matches(ClassReflection $classReflection): bool
    {
        return $classReflection->isAbstract();
    }
}
