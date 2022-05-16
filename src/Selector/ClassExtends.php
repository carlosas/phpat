<?php

namespace PhpAT\Selector;

use PHPStan\Reflection\ClassReflection;

class ClassExtends implements Selector
{
    private string $classname;

    public function __construct(string $classname)
    {
        $this->classname = $classname;
    }

    public function matches(ClassReflection $classReflection): bool
    {
        $parent = $classReflection->getParentClass();
        if ($parent === null) {
            return false;
        }

        return $parent->getName() === $this->classname;
    }
}