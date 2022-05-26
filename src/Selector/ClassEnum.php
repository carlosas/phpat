<?php

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

class ClassEnum implements SelectorInterface
{
    public function matches(ClassReflection $classReflection): bool
    {
        return $classReflection->isEnum();
    }
}
