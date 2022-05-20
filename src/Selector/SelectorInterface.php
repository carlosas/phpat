<?php

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

interface SelectorInterface
{
    /**
     * @param class-string $classname
     */
    public function __construct(string $classname);

    public function matches(ClassReflection $classReflection): bool;
}
