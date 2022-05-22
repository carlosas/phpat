<?php

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;
use function removePrefixAndSuffixSeparators;

class Classname implements SelectorInterface
{
    private string $classname;

    /**
     * @param class-string $classname
     */
    public function __construct(string $classname)
    {
        $this->classname = $classname;
    }

    public function matches(ClassReflection $classReflection): bool
    {
        return removePrefixAndSuffixSeparators($classReflection->getName())
            === removePrefixAndSuffixSeparators($this->classname);
    }
}
