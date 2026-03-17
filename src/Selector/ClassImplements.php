<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

final class ClassImplements implements SelectorInterface
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
            if (preg_match($this->classname, $interface->getName()) === 1) {
                return true;
            }
        }

        return false;
    }
}
