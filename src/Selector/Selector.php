<?php

declare(strict_types=1);

namespace PHPat\Selector;

class Selector
{
    public static function all(): All
    {
        return new All();
    }

    public static function interface(): IsInterface
    {
        return new IsInterface();
    }

    public static function abstract(): IsAbstract
    {
        return new IsAbstract();
    }

    public static function final(): IsFinal
    {
        return new IsFinal();
    }

    public static function enum(): IsEnum
    {
        return new IsEnum();
    }

    public static function namespace(string $namespace): ClassNamespace
    {
        return new ClassNamespace($namespace);
    }

    public static function classname(string $fqcn): Classname
    {
        return new Classname($fqcn);
    }

    public static function implements(string $fqcn): ClassImplements
    {
        return new ClassImplements($fqcn);
    }

    public static function extends(string $fqcn): ClassExtends
    {
        return new ClassExtends($fqcn);
    }
}
