<?php declare(strict_types=1);

namespace PHPat\Selector;

final class IsTrait implements SelectorInterface
{
    public function getName(): string
    {
        return '-all traits-';
    }

    /**
     * @param \ReflectionClass<object> $classReflection
     */
    public function matches(\ReflectionClass $classReflection): bool
    {
        return $classReflection->isTrait();
    }
}
