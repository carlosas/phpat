<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

final class ClassExtends implements SelectorInterface
{
    private string $classname;
    private bool $isRegex;

    /**
     * @param class-string|string $classname
     */
    public function __construct(string $classname, bool $isRegex)
    {
        $this->classname = $classname;
        $this->isRegex = $isRegex;
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

        $classname = trimSeparators($this->classname);
        foreach ($parents as $parent) {
            if ($parent->getName() === $classname) {
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
            if (preg_match($this->classname, $parent->getName()) === 1) {
                return true;
            }
        }

        return false;
    }
}
