<?php

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;
use function trimSeparators;

class Classname implements SelectorInterface
{
    private string $classname;
    private bool $isRegex;

    /**
     * @param class-string|string $classname
     */
    public function __construct(string $classname)
    {
        $this->classname = $classname;
        $this->isRegex = isRegularExpression($classname);
    }

    public function matches(ClassReflection $classReflection): bool
    {
        if ($this->isRegex) {
            return (
                preg_match($this->classname, $classReflection->getName()) > 0
                || preg_match($this->classname, trimSeparators($classReflection->getName())) > 0
            );
        }

        return trimSeparators($classReflection->getName()) === trimSeparators($this->classname);
    }
}
