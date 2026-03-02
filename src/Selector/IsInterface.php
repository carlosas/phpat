<?php declare(strict_types=1);

namespace PHPat\Selector;

final class IsInterface implements SelectorInterface
{
    public function getName(): string
    {
        return '-all interfaces-';
    }

    /**
     * @param \ReflectionClass<object> $classReflection
     */
    public function matches(\ReflectionClass $classReflection): bool
    {
        return $classReflection->isInterface();
    }
}
