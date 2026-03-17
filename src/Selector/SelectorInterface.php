<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

interface SelectorInterface
{
    public function matches(ClassReflection $classReflection): bool;

    public function getName(): string;
}
