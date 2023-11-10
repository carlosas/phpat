<?php declare(strict_types=1);

namespace PHPat\Selector;

class SelectorPrimitive
{
    public static function all(): All
    {
        return new All();
    }

    /**
     * @deprecated use Selector::isInterface()
     */
    public static function interface(): IsInterface
    {
        return self::isInterface();
    }

    public static function isInterface(): IsInterface
    {
        return new IsInterface();
    }

    /**
     * @deprecated use Selector::isAbstract()
     */
    public static function abstract(): IsAbstract
    {
        return self::isAbstract();
    }

    public static function isAbstract(): IsAbstract
    {
        return new IsAbstract();
    }

    /**
     * @deprecated use Selector::NOT(Selector::isAbstract())
     */
    public static function notAbstract(): IsNotAbstract
    {
        return new IsNotAbstract();
    }

    /**
     * @deprecated use Selector::isFinal()
     */
    public static function final(): IsFinal
    {
        return self::isFinal();
    }

    public static function isFinal(): IsFinal
    {
        return new IsFinal();
    }

    /**
     * @deprecated use Selector::isReadonly()
     */
    public static function readonly(): IsReadonly
    {
        return self::isReadonly();
    }

    public static function isReadonly(): IsReadonly
    {
        return new IsReadonly();
    }

    /**
     * @deprecated use Selector::NOT(Selector::isFinal())
     */
    public static function notFinal(): IsNotFinal
    {
        return new IsNotFinal();
    }

    /**
     * @deprecated use Selector::isEnum()
     */
    public static function enum(): IsEnum
    {
        return self::isEnum();
    }

    public static function isEnum(): IsEnum
    {
        return new IsEnum();
    }

    /**
     * @deprecated use Selector::isAttribute()
     */
    public static function attribute(): IsAttribute
    {
        return self::isAttribute();
    }

    public static function isAttribute(): IsAttribute
    {
        return new IsAttribute();
    }

    /**
     * @param class-string|non-empty-string $namespace
     *
     * @deprecated use Selector::inNamespace()
     */
    public static function namespace(string $namespace, bool $regex = false): ClassNamespace
    {
        return self::inNamespace($namespace, $regex);
    }

    /**
     * @param class-string|non-empty-string $namespace
     */
    public static function inNamespace(string $namespace, bool $regex = false): ClassNamespace
    {
        return new ClassNamespace($namespace, $regex);
    }

    /**
     * @param class-string|non-empty-string $fqcn
     */
    public static function classname(string $fqcn, bool $regex = false): Classname
    {
        return new Classname($fqcn, $regex);
    }

    /**
     * @param class-string|non-empty-string $fqcn
     */
    public static function implements(string $fqcn, bool $regex = false): ClassImplements
    {
        return new ClassImplements($fqcn, $regex);
    }

    /**
     * @param class-string|non-empty-string $fqcn
     */
    public static function extends(string $fqcn, bool $regex = false): ClassExtends
    {
        return new ClassExtends($fqcn, $regex);
    }

    /**
     * @param class-string|non-empty-string $fqcn
     *
     * @deprecated use Selector::appliesAttribute()
     */
    public static function hasAttribute(string $fqcn, bool $regex = false): AppliesAttribute
    {
        return self::appliesAttribute($fqcn, $regex);
    }

    /**
     * @param class-string|non-empty-string $fqcn
     */
    public static function appliesAttribute(string $fqcn, bool $regex = false): AppliesAttribute
    {
        return new AppliesAttribute($fqcn, $regex);
    }
}
