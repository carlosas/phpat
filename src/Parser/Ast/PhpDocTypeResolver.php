<?php

namespace PhpAT\Parser\Ast;

use PHPStan\PhpDocParser\Ast\Type;

/**
 * Class PhpDocTypeResolver
 * @package PhpAT\Parser\Ast
 * Based on SensioLabs/Deptrac TypeResolver
 */
class PhpDocTypeResolver
{
    private const BUILTIN_TYPES = [
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
    ];

    /**
     * @param Type\TypeNode $type
     * @return string[]
     */
    public function resolve(Type\TypeNode $type): array
    {
        if ($type instanceof Type\IdentifierTypeNode && !$this->isBuiltinType($type->name)) {
            return [$type->name];
        }

        if ($type instanceof Type\ArrayTypeNode || $type instanceof Type\NullableTypeNode) {
            return $this->resolve($type->type);
        }

        return [];
    }

    private function isBuiltinType(string $type): bool
    {
        return in_array($type, self::BUILTIN_TYPES, true);
    }
}
