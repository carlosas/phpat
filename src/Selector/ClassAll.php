<?php

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;
use function trimSeparators;

class ClassAll implements SelectorInterface
{
    public function matches(ClassReflection $classReflection): bool
    {
        return true;
    }
}
