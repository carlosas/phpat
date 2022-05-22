<?php

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;
use function removePrefixAndSuffixSeparators;

class ClassAll implements SelectorInterface
{
    public function matches(ClassReflection $classReflection): bool
    {
        return true;
    }
}
