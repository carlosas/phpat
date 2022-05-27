<?php

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

class ClassImplements implements SelectorInterface
{
    private string $classname;
    private bool $isRegex;

    /**
     * @param class-string|string $classname
     */
    public function __construct(string $classname)
    {
        $this->classname = $classname;
        $this->isRegex   = isRegularExpression($classname);
    }

    public function matches(ClassReflection $classReflection): bool
    {
        if ($this->isRegex) {
            return $this->matchesRegex($classReflection->getInterfaces());
        }

        return $classReflection->implementsInterface($this->classname);
    }

    /**
     * @param array<ClassReflection> $interfaces
     */
    private function matchesRegex(array $interfaces): bool
    {
        foreach ($interfaces as $interface) {
            if (
                preg_match($this->classname, $interface->getName()) > 0
                || preg_match($this->classname, trimSeparators($interface->getName())) > 0
            ) {
                return true;
            }
        }

        return false;
    }
}
