<?php declare(strict_types=1);

namespace PHPat\Selector;

final class IsNotAbstract implements SelectorInterface
{
    public function getName(): string
    {
        return '-non abstract classes-';
    }

    /**
     * @param \ReflectionClass<object> $classReflection
     */
    public function matches(\ReflectionClass $classReflection): bool
    {
        return !$classReflection->isAbstract();
    }
}
