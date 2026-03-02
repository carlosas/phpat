<?php declare(strict_types=1);

namespace PHPat\Selector;

final class IsAbstract implements SelectorInterface
{
    public function getName(): string
    {
        return '-abstract classes-';
    }

    /**
     * @param \ReflectionClass<object> $classReflection
     */
    public function matches(\ReflectionClass $classReflection): bool
    {
        return $classReflection->isAbstract();
    }
}
