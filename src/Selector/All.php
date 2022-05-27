<?php

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

class All implements SelectorInterface
{
    public function getName(): string
    {
        return '-all classes-';
    }

    public function matches(ClassReflection $classReflection): bool
    {
        return true;
    }
}
