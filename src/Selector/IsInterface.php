<?php

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

class IsInterface implements SelectorInterface
{
    public function matches(ClassReflection $classReflection): bool
    {
        return $classReflection->isInterface();
    }
}
