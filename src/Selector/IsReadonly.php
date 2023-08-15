<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

final class IsReadonly implements SelectorInterface
{
    public function getName(): string
    {
        return '-all readonly classes-';
    }

    public function matches(ClassReflection $classReflection): bool
    {
        return $classReflection->isReadOnly();
    }
}
