<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPStan\Reflection\ClassReflection;

final class IsEnum implements SelectorInterface
{
    public function getName(): string
    {
        return '-all enums-';
    }

    public function matches(ClassReflection $classReflection): bool
    {
        if (PHP_VERSION_ID < 80100) {
            return false;
        }

        // @phpstan-ignore-next-line
        return $classReflection->isEnum();
    }
}
