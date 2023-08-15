<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

class IsInterface implements SelectorInterface
{
    public function getName(): string
    {
        return '-all interfaces-';
    }

    public function matches(ClassReflection $classReflection): bool
    {
        return $classReflection->isInterface();
    }
}
