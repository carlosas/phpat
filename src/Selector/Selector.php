<?php

declare(strict_types=1);

namespace PHPat\Selector;

class Selector
{
    public static function any(): ClassAll
    {
        return self::all();
    }

    public static function all(): ClassAll
    {
        return new ClassAll();
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
