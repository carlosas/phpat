<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPStan\BetterReflection\Reflection\ReflectionAttribute;
use PHPStan\Reflection\ClassReflection;

final class AppliesAttribute implements SelectorInterface
{
    private string $attributeName;
    private bool $isRegex;

    /** @var array<string, mixed> */
    private array $arguments;

    /**
     * @param class-string|string  $attributeName
     * @param array<string, mixed> $arguments
     */
    public function __construct(string $attributeName, array $arguments = [], bool $isRegex = false)
    {
        $this->attributeName = $attributeName;
        $this->isRegex = $isRegex;
        $this->arguments = $arguments;
    }

    public function getName(): string
    {
        return $this->attributeName;
    }

    public function matches(ClassReflection $classReflection): bool
    {
        /** @var list<ReflectionAttribute> $attributes */
        $attributes = $classReflection->getNativeReflection()->getAttributes();

        if ($this->isRegex) {
            return $this->matchesRegex($attributes);
        }

        foreach ($attributes as $attribute) {
            if ($attribute->getName() === $this->attributeName) {
                $arguments = $attribute->getArguments();

                if (count($this->arguments) > 0) {
                    return $this->matchesArguments($arguments);
                }

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
        /** @var ReflectionAttribute $attribute */
        foreach ($attributes as $attribute) {
            if (preg_match($this->attributeName, $attribute->getName()) === 1) {
                $arguments = $attribute->getArguments();

                if (count($this->arguments) > 0) {
                    return $this->matchesArguments($arguments);
                }

                return true;
            }
        }

        return false;
    }

    /**
     * @param array<int|string, mixed> $arguments
     */
    private function matchesArguments(array $arguments): bool
    {
        $keys = array_intersect($arguments, $this->arguments);

        if (count($keys) === 0) {
            return false;
        }

        foreach ($keys as $key) {
            if ($arguments[$key] !== $this->arguments[$key]) {
                return false;
            }
        }

        return true;
    }
}
