<?php

namespace PhpAT\Parser\Ast;

class PhpType
{
    public const BUILTIN_TYPES = [
        'array',
        'callable',
        'string',
        'int',
        'float',
        'double',
        'bool',
        'iterable',
        'void',
        'object',
        'mixed',
        'resource',
        'null'
    ];

    public const SPECIAL_TYPES = [
        'self',
        'parent',
        'static'
    ];

    public static function isBuiltinType(string $type): bool
    {
        return in_array($type, PhpType::BUILTIN_TYPES, true);
    }

    public static function isSpecialType(string $type): bool
    {
        return in_array($type, PhpType::SPECIAL_TYPES, true);
    }

    public static function isAutoloaded(string $type): bool
    {
        return (class_exists($type) || interface_exists($type) || trait_exists($type));
    }
}
