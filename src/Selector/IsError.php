<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

final class IsError implements SelectorInterface
{
    public function getName(): string
    {
        return '-all errors-';
    }

    public function matches(ClassReflection $classReflection): bool
    {
        return $classReflection->isSubclassOf(\Error::class);
    }
}
