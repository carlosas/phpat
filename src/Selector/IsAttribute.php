<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

final class IsAttribute implements SelectorInterface
{
    public function getName(): string
    {
        return '-attribute classes-';
    }

    public function matches(ClassReflection $classReflection): bool
    {
        return $classReflection->isAttributeClass();
    }
}
