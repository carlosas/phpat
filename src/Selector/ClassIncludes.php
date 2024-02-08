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
        if ($this->isRegex) {
            return $this->matchesRegex($classReflection->getTraits());
        }

        return $classReflection->hasTraitUse($this->traitname);
    }

    /**
     * @param array<ClassReflection> $traits
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
}
