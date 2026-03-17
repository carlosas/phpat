<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

final class All implements SelectorInterface
{
    public function getName(): string
    {
        return '-all classes-';
    }

    public function matches(ClassReflection $classReflection): bool
    {
        return true;
    }
}
