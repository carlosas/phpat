<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

final class IsThrowable implements SelectorInterface
{
    public function getName(): string
    {
        return '-all throwables-';
    }

    public function matches(ClassReflection $classReflection): bool
    {
        return $classReflection->implementsInterface(\Throwable::class);
    }
}
