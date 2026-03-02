<?php declare(strict_types=1);

namespace PHPat\Selector;

final class All implements SelectorInterface
{
    public function getName(): string
    {
        return '-all classes-';
    }

    /**
     * @param \ReflectionClass<object> $classReflection
     */
    public function matches(\ReflectionClass $classReflection): bool
    {
        return true;
    }
}
