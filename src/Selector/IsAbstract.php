<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

final class IsAbstract implements SelectorInterface
{
    public function getName(): string
    {
        return '-abstract classes-';
    }

    public function matches(ClassReflection $classReflection): bool
    {
        return $classReflection->isAbstract();
    }
}
