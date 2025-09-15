<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPat\Parser\BuiltInClasses;
use PHPStan\Reflection\ClassReflection;

final class IsStandardClass implements SelectorInterface
{
    public function getName(): string
    {
        return '-standard classes-';
    }

    public function matches(ClassReflection $classReflection): bool
    {
        return in_array($classReflection->getName(), BuiltInClasses::PHP_BUILT_IN_CLASSES, true);
    }
}
