<?php

declare(strict_types=1);

namespace PHPat\Parser;

use PhpParser\Node;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Identifier;
use PhpParser\Node\IntersectionType;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\UnionType;
use PhpParser\NodeAbstract;
use PHPStan\Analyser\Scope;

class TypeNodeParser
{
    /**
     * @return array<Node\Name>
     */
    public static function parse(?NodeAbstract $type, Scope $scope): array
    {
        if ($type instanceof Node\Name) {
            return [self::parseName($type, $scope)];
        }

        if ($type instanceof Node\ComplexType) {
            return self::parseComplex($type, $scope);
        }

        return [];
    }

    /**
     * @return array<Name>
     */
    private static function parseComplex(ComplexType $type, Scope $scope): array
    {
        switch (true) {
            case $type instanceof NullableType:
                $toParse = [$type->type];
                break;
            case $type instanceof UnionType:
            case $type instanceof IntersectionType:
                $toParse = $type->types;
                break;
            default:
                return [];
        }

        return array_map(
            static fn (Name $n) => self::parseName($n, $scope),
            self::filterNameNodes($toParse)
        );
    }

    /**
     * @param array<Identifier|Name> $type
     * @return array<Name>
     */
    private static function filterNameNodes(array $type): array
    {
        return array_filter($type, static fn ($type) => $type instanceof Name);
    }

    private static function parseName(Name $type, Scope $scope): Name
    {
        if ($type->isFullyQualified()) {
            return $type;
        }

        return new Name($scope->resolveName($type));
    }
}
