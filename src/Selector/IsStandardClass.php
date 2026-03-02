<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPat\Parser\BuiltInClasses;

final class IsStandardClass implements SelectorInterface
{
    public function getName(): string
    {
        return '-standard classes-';
    }

    /**
     * @param \ReflectionClass<object> $classReflection
     */
    public function matches(\ReflectionClass $classReflection): bool
    {
        return in_array($classReflection->getName(), BuiltInClasses::PHP_BUILT_IN_CLASSES, true);
    }
}
