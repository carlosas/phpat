<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

final class IsEnum implements SelectorInterface
{
    public function getName(): string
    {
        return '-all enums-';
    }

    /**
     * @param ClassReflection $classReflection
     */
    public function matches($classReflection): bool
    {
        return $classReflection->isEnum();
    }
}
