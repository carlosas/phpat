<?php

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

interface SelectorInterface
{
    public function matches(ClassReflection $classReflection): bool;
}
