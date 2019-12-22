<?php

declare(strict_types=1);

namespace PhpAT\Selector;

class Selector
{
    public static function havePath(string $path): PathSelector
    {
        return new PathSelector($path);
    }

    public static function haveClassName(string $fqcn): ClassNameSelector
    {
        return new ClassNameSelector($fqcn);
    }

    public static function implementInterface(string $fqcn): ImplementSelector
    {
        return new ImplementSelector($fqcn);
    }

    public static function extendClass(string $fqcn): ExtendSelector
    {
        return new ExtendSelector($fqcn);
    }
}
