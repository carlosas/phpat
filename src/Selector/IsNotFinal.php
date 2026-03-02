<?php declare(strict_types=1);

namespace PHPat\Selector;

final class IsNotFinal implements SelectorInterface
{
    public function getName(): string
    {
        return '-non final classes-';
    }

    /**
     * @param \ReflectionClass<object> $classReflection
     */
    public function matches(\ReflectionClass $classReflection): bool
    {
        return !$classReflection->isFinal();
    }
}
