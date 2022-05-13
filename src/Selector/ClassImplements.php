<?php

namespace PhpAT\Selector;

use PHPStan\Reflection\ClassReflection;

class ClassImplements implements Selector
{
    private string $classname;

    public function __construct(string $classname)
    {
        $this->classname = $classname;
    }

    public function matches(ClassReflection $classReflection): bool
    {
        return $classReflection->implementsInterface($this->classname);
    }
}
