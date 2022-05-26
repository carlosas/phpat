<?php

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;
use function trimSeparators;

class ClassAbstract implements SelectorInterface
{
    public function matches(ClassReflection $classReflection): bool
    {
        return $classReflection->isAbstract();
    }
}
