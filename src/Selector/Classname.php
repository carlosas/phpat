<?php

namespace PhpAT\Selector;

use PHPStan\Reflection\ClassReflection;

class Classname implements Selector
{
    private string $classname;

    public function __construct(string $classname)
    {
        $this->classname = $classname;
    }

    public function matches(ClassReflection $classReflection): bool
    {
        var_dump($classReflection->getName());
        var_dump($this->classname);

        return $classReflection->getName() === $this->classname;
    }
}
