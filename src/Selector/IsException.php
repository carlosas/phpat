<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

final class IsException implements SelectorInterface
{
    public function getName(): string
    {
        return '-all exceptions-';
    }

    public function matches(ClassReflection $classReflection): bool
    {
        return $classReflection->isSubclassOf(\Exception::class);
    }
}
