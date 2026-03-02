<?php declare(strict_types=1);

namespace PHPat\Selector;

final class IsThrowable implements SelectorInterface
{
    public function getName(): string
    {
        return '-all throwables-';
    }

    /**
     * @param \ReflectionClass<object> $classReflection
     */
    public function matches(\ReflectionClass $classReflection): bool
    {
        return $classReflection->implementsInterface(\Throwable::class);
    }
}
