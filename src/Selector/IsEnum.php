<?php declare(strict_types=1);

namespace PHPat\Selector;

final class IsEnum implements SelectorInterface
{
    public function getName(): string
    {
        return '-all enums-';
    }

    /**
     * @param \ReflectionClass<object> $classReflection
     */
    public function matches(\ReflectionClass $classReflection): bool
    {
        return $classReflection->isEnum();
    }
}
