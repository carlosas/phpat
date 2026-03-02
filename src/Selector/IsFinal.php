<?php declare(strict_types=1);

namespace PHPat\Selector;

final class IsFinal implements SelectorInterface
{
    public function getName(): string
    {
        return '-all final classes-';
    }

    /**
     * @param \ReflectionClass<object> $classReflection
     */
    public function matches(\ReflectionClass $classReflection): bool
    {
        return $classReflection->isFinal();
    }
}
