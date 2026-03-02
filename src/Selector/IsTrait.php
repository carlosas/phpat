<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

final class IsTrait implements SelectorInterface
{
    public function getName(): string
    {
        return '-all traits-';
    }

    /**
     * @param \ReflectionClass<object> $classReflection
     */
    /**
     * @param ClassReflection $classReflection
     */
    public function matches($classReflection): bool
    {
        return $classReflection->isTrait();
    }
}
