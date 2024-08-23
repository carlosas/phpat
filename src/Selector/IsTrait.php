<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

final class IsTrait implements SelectorInterface
{
    public function getName(): string
    {
        return '-all traits-';
    }

    public function matches(ClassReflection $classReflection): bool
    {
        return $classReflection->isTrait();
    }
}
