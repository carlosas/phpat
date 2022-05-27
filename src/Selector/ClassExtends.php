<?php

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

class ClassExtends implements SelectorInterface
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

    public function getName(): string
    {
        return $this->classname;
    }

    public function matches(ClassReflection $classReflection): bool
    {
        $parents = $classReflection->getParents();
        if (empty($parents)) {
            return false;
        }

        if ($this->isRegex) {
            return $this->matchesRegex($parents);
        }

        foreach ($parents as $parent) {
            if (trimSeparators($parent->getName()) === trimSeparators($this->classname)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<ClassReflection> $parents
     */
    private function matchesRegex(array $parents): bool
    {
        foreach ($parents as $parent) {
            if (
                preg_match($this->classname, $parent->getName()) > 0
                || preg_match($this->classname, trimSeparators($parent->getName())) > 0
            ) {
                return true;
            }
        }

        return false;
    }
}
