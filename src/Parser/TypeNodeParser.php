<?php declare(strict_types=1);

namespace PHPat\Parser;

use PHPat\ShouldNotHappenException;
use PhpParser\Node;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Identifier;
use PhpParser\Node\IntersectionType;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\UnionType;
use PhpParser\NodeAbstract;
use PHPStan\Analyser\Scope;

final class TypeNodeParser
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
        return array_map(
            static fn (Name $n) => self::parseName($n, $scope),
            array_filter(self::flattenType($type), static fn ($type) => $type instanceof Name)
        );
    }

    /**
     * @return array<Identifier|Name>
     */
    private static function flattenType(NodeAbstract $type): array
    {
        switch (true) {
            case $type instanceof NullableType:
                return self::flattenType($type->type);
            case $type instanceof UnionType:
            case $type instanceof IntersectionType:
                return array_merge_recursive(
                    ...array_values(
                        array_map(
                            static fn (NodeAbstract $n) => self::flattenType($n),
                            $type->types
                        )
                    )
                );
            case $type instanceof Name:
            case $type instanceof Identifier:
                return [$type];
            default:
                throw new ShouldNotHappenException();
        }
    }

    private static function parseName(Name $type, Scope $scope): Name
    {
        if ($type->isFullyQualified()) {
            return $type;
        }

        return new Name($scope->resolveName($type));
    }
}
