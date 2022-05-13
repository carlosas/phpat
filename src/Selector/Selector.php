<?php

namespace PhpAT\Selector;

use PHPStan\Reflection\ClassReflection;

interface Selector
{
    /**
     * @param class-string $classname
     */
    public function __construct(string $classname);

    public function matches(ClassReflection $classReflection): bool;
}
