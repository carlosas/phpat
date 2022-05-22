<?php

namespace PHPat\Parser;

use PhpParser\Node\ComplexType;
use PhpParser\Node\Identifier;
use PhpParser\Node\IntersectionType;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\UnionType;

class ComplexTypeParser
{
    /**
     * @return iterable<class-string>
     */
    public static function parse(ComplexType $type): iterable
    {
        switch (true) {
            case $type instanceof NullableType:
                return self::filterNameNodes([$type->type]);
            case $type instanceof UnionType:
            case $type instanceof IntersectionType:
                return self::filterNameNodes($type->types);
            default:
                return [];
        }
    }

    /**
     * @param iterable<Identifier|Name> $type
     * @return iterable<class-string>
     */
    private static function filterNameNodes(iterable $type): iterable
    {
        return array_map(
            static fn (Name $type): string => $type->toString(),
            array_filter($type, static function ($type) {
                return $type instanceof Name;
            })
        );
    }
}
