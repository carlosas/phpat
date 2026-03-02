<?php declare(strict_types=1);

namespace PHPat\Selector;

interface SelectorInterface
{
    /**
     * @param \ReflectionClass<object> $classReflection
     */
    /**
     * @param \ReflectionClass<object> $classReflection
     */
    public function matches(\ReflectionClass $classReflection): bool;

    public function getName(): string;
}
