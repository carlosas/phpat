<?php declare(strict_types=1);

namespace PHPat\Selector;

final class IsAttribute implements SelectorInterface
{
    public function getName(): string
    {
        return '-attribute classes-';
    }

    /**
     * @param \ReflectionClass<object> $classReflection
     */
    public function matches(\ReflectionClass $classReflection): bool
    {
        return count($classReflection->getAttributes(\Attribute::class)) > 0;
    }
}
