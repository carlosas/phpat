<?php declare(strict_types=1);

namespace PHPat\Selector;

class SelectorPrimitive
{
    public static function all(): All
    {
        return new All();
    }

    public static function isException(): IsException
    {
        return new IsException();
    }

    public static function isThrowable(): IsThrowable
    {
        return new IsThrowable();
    }

    public static function isError(): IsError
    {
        return new IsError();
    }

    public static function isInterface(): IsInterface
    {
        return new IsInterface();
    }

    public static function isAbstract(): IsAbstract
    {
        return new IsAbstract();
    }

    public static function isFinal(): IsFinal
    {
        return new IsFinal();
    }

    public static function isTrait(): IsTrait
    {
        return new IsTrait();
    }

    public static function isReadonly(): IsReadonly
    {
        return new IsReadonly();
    }

    public static function isEnum(): IsEnum
    {
        return new IsEnum();
    }

    public static function isAttribute(): IsAttribute
    {
        return new IsAttribute();
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

    public static function filepath(string $filename, bool $regex = false): Filepath
    {
        return new Filepath($filename, $regex);
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
     * @param array<string, mixed>          $arguments
     */
    public static function appliesAttribute(string $fqcn, bool $regex = false, array $arguments = []): AppliesAttribute
    {
        return new AppliesAttribute($fqcn, $regex, $arguments);
    }

    /**
     * @param class-string|non-empty-string $fqcn
     */
    public static function includes(string $fqcn, bool $regex = false): ClassIncludes
    {
        return new ClassIncludes($fqcn, $regex);
    }
}
