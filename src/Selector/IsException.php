<?php declare(strict_types=1);

namespace PHPat\Selector;

final class IsException implements SelectorInterface
{
    public function getName(): string
    {
        return '-all exceptions-';
    }

    /**
     * @param \ReflectionClass<object> $classReflection
     */
    public function matches(\ReflectionClass $classReflection): bool
    {
        return $classReflection->isSubclassOf(\Exception::class);
    }
}
