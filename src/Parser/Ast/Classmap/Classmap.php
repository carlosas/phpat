<?php

namespace PhpAT\Parser\Ast\Classmap;

use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Ast\SrcNode;
use PhpAT\Parser\Ast\Type\PhpType;
use PhpAT\Parser\Relation\AbstractRelation;
use PhpAT\Parser\Relation\Composition;
use PhpAT\Parser\Relation\Dependency;
use PhpAT\Parser\Relation\Inheritance;
use PhpAT\Parser\Relation\Mixin;

final class Classmap
{
    /** @var ClassmapItem[] */
    private static $classmap = [];

    public static function registerClass(
        FullClassName $className,
        string $pathname,
        string $classType,
        ?int $flag
    ) {
        if (!isset(static::$classmap[$className->getFQCN()])) {
            static::$classmap[$className->getFQCN()] = new ClassmapItem($pathname, $classType, $flag);
        }
    }

    public static function registerClassImplements(
        FullClassName $classImplementing,
        FullClassName $classImplemented,
        int $startLine,
        int $endLine
    ) {
        static::$classmap[$classImplementing->getFQCN()]->addInterface(
            new ClassmapRelation($classImplemented, $startLine, $endLine)
        );
    }

    public static function registerClassExtends(
        FullClassName $classExtending,
        FullClassName $classExtended,
        int $startLine,
        int $endLine
    ) {
        static::$classmap[$classExtending->getFQCN()]->addParent(
            new ClassmapRelation($classExtended, $startLine, $endLine)
        );
    }

    public static function registerClassIncludesTrait(
        FullClassName $classIncluding,
        FullClassName $classIncluded,
        int $startLine,
        int $endLine
    ) {
        static::$classmap[$classIncluding->getFQCN()]->addTrait(
            new ClassmapRelation($classIncluded, $startLine, $endLine)
        );
    }

    public static function registerClassDepends(
        FullClassName $classDepending,
        FullClassName $classDepended,
        int $startLine,
        int $endLine
    ) {
        if (PhpType::isBuiltinType($classDepended->getFQCN()) || PhpType::isSpecialType($classDepended->getFQCN())) {
            return;
        }

        static::$classmap[$classDepending->getFQCN()]->addDependency(
            new ClassmapRelation($classDepended, $startLine, $endLine)
        );
    }

    public static function getClassmap(): array
    {
        return static::translateClassmap(static::$classmap);
    }

    /**
     * Temporary BC structure
     */
    private static function translateClassmap(array $classmap): array
    {
        /** @var ClassmapItem $properties */
        foreach ($classmap as $className => $properties) {
            $srcNodes[$className] = new SrcNode(
                $properties->getPathname(),
                FullClassName::createFromFQCN($className),
                array_merge(
                    static::addRelations(Dependency::class, $properties->getDependencies()),
                    static::addRelations(
                        Inheritance::class,
                        $properties->getParent() === null ? [] : [$properties->getParent()]
                    ),
                    static::addRelations(Composition::class, $properties->getInterfaces()),
                    static::addRelations(Mixin::class, $properties->getTraits())
                )
            );
        }

        return $srcNodes ?? [];
    }

    /**
     * @param ClassmapRelation[] $classmapRelations
     * @return AbstractRelation[]
     */
    private static function addRelations(string $type, array $classmapRelations): array
    {
        foreach ($classmapRelations as $relation) {
            $result[] = new $type($relation->relatedClass, $relation->startLine, $relation->endLine);
        }

        return $result ?? [];
    }
}
