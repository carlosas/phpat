<?php

namespace PhpAT\Parser\Ast\Type;

class PhpType
{
    public const BUILTIN_TYPES = [
        'array',
        'callable',
        'string',
        'int',
        'integer',
        'float',
        'double',
        'bool',
        'boolean',
        'iterable',
        'void',
        'object',
        'mixed',
        'resource',
        'null',
        'true',
        'false'
    ];

    public const SPECIAL_TYPES = [
        'self',
        'parent',
        'static',
        '$this'
    ];

    public static function isBuiltinType(string $type): bool
    {
        return in_array($type, PhpType::BUILTIN_TYPES, true);
    }

    public static function isSpecialType(string $type): bool
    {
        return in_array($type, PhpType::SPECIAL_TYPES, true);
    }
}
