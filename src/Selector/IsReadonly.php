<?php declare(strict_types=1);

namespace PHPat\Selector;

final class IsReadonly implements SelectorInterface
{
    public function getName(): string
    {
        return '-all readonly classes-';
    }

    /**
     * @param \ReflectionClass<object> $classReflection
     */
    public function matches(\ReflectionClass $classReflection): bool
    {
        return $classReflection->isReadOnly();
    }
}
