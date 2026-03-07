<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

final class IsAttribute implements SelectorInterface
{
    public function getName(): string
    {
        return '-attribute classes-';
    }

    /**
     * @param ClassReflection $classReflection
     */
    public function matches($classReflection): bool
    {
        return !empty($classReflection->getNativeReflection()->getAttributes(\Attribute::class));
    }
}
