<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

class IsFinal implements SelectorInterface
{
    public function getName(): string
    {
        return '-all final classes-';
    }

    public function matches(ClassReflection $classReflection): bool
    {
        return $classReflection->isFinal();
    }
}
