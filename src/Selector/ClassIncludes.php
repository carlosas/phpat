<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

final class ClassIncludes implements SelectorInterface
{
    private string $traitname;
    private bool $isRegex;

    /**
     * @param class-string|string $traitname
     */
    public function __construct(string $traitname, bool $isRegex)
    {
        $this->traitname = $traitname;
        $this->isRegex = $isRegex;
    }

    public function getName(): string
    {
        return $this->traitname;
    }

    public function matches(ClassReflection $classReflection): bool
    {
        $traits = $this->getAllTraits($classReflection);

        if ($this->isRegex) {
            return $this->matchesRegex($traits);
        }

        foreach ($traits as $trait) {
            if ($trait->getName() === trimSeparators($this->traitname)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<string, ClassReflection> $traits
     */
    private function matchesRegex(array $traits): bool
    {
        foreach ($traits as $trait) {
            if (preg_match($this->traitname, $trait->getName()) === 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  ClassReflection                $classReflection
     * @return array<string, ClassReflection>
     */
    private function getAllTraits($classReflection): array
    {
        $traits = [];

        foreach ($classReflection->getTraits() as $trait) {
            $traits[$trait->getName()] = $trait;
            $traits = array_merge($traits, $this->getAllTraits($trait));
        }

        $parent = $classReflection->getParentClass();
        if ($parent !== null) {
            $traits = array_merge($traits, $this->getAllTraits($parent));
        }

        return $traits;
    }
}
