<?php

namespace PhpAT\Parser\Ast;

use PHPStan\PhpDocParser\Ast\Type;

/**
 * Class PhpDocTypeResolver
 * @package PhpAT\Parser\Ast
 * Based on sensiolabs-de/deptrac TypeResolver
 * Copyright (c) 2016 | MIT License
 */
class PhpDocTypeResolver
{
    /**
     * @param Type\TypeNode $type
     * @return string[]
     */
    public function resolve(Type\TypeNode $type): array
    {
        if (
            $type instanceof Type\IdentifierTypeNode
            && !PhpType::isBuiltinType($type->name)
            && !PhpType::isSpecialType($type->name)
        ) {
            return [$type->name];
        }

        if ($type instanceof Type\ArrayTypeNode || $type instanceof Type\NullableTypeNode) {
            return $this->resolve($type->type);
        }

        return [];
    }
}
