<?php

declare(strict_types=1);

namespace PHPat\Selector;

class SelectorPrimitive
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

    public static function notAbstract(): IsNotAbstract
    {
        return new IsNotAbstract();
    }

    public static function final(): IsFinal
    {
        return new IsFinal();
    }

    public static function notFinal(): IsNotFinal
    {
        return new IsNotFinal();
    }

    public static function enum(): IsEnum
    {
        return new IsEnum();
    }

    public static function attribute(): IsAttribute
    {
        return new IsAttribute();
    }

    public static function namespace(string $namespace, bool $regex = false): ClassNamespace
    {
        return new ClassNamespace($namespace, $regex);
    }

    public static function classname(string $fqcn, bool $regex = false): Classname
    {
        return new Classname($fqcn, $regex);
    }

    public static function implements(string $fqcn, bool $regex = false): ClassImplements
    {
        return new ClassImplements($fqcn, $regex);
    }

    public static function extends(string $fqcn, bool $regex = false): ClassExtends
    {
        return new ClassExtends($fqcn, $regex);
    }
}
