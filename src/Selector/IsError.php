<?php declare(strict_types=1);

namespace PHPat\Selector;

final class IsError implements SelectorInterface
{
    public function getName(): string
    {
        return '-all errors-';
    }

    /**
     * @param \ReflectionClass<object> $classReflection
     */
    public function matches(\ReflectionClass $classReflection): bool
    {
        return $classReflection->isSubclassOf(\Error::class);
    }
}
