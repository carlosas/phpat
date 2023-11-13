<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPStan\BetterReflection\Reflection\ReflectionAttribute;
use PHPStan\Reflection\ClassReflection;

final class AppliesAttribute implements SelectorInterface
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
        /** @var list<ReflectionAttribute> $attributes */
        $attributes = $classReflection->getNativeReflection()->getAttributes();

        if ($this->isRegex) {
            return $this->matchesRegex($attributes);
        }

        foreach ($attributes as $attribute) {
            if ($attribute->getName() === $this->classname) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param list<ReflectionAttribute> $attributes
     */
    private function matchesRegex(array $attributes): bool
    {
        foreach ($attributes as $attribute) {
            if (preg_match($this->classname, $attribute->getName()) === 1) {
                return true;
            }
        }

        return false;
    }
}
