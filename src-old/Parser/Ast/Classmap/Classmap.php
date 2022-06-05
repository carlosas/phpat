<?php

namespace PHPatOld\Parser\Ast\Classmap;

use PHPatOld\Parser\Ast\FullClassName;
use PHPatOld\Parser\Ast\SrcNode;
use PHPatOld\Parser\Ast\Type\PhpType;
use PHPatOld\Parser\Relation\AbstractRelation;
use PHPatOld\Parser\Relation\Composition;
use PHPatOld\Parser\Relation\Dependency;
use PHPatOld\Parser\Relation\Inheritance;
use PHPatOld\Parser\Relation\Mixin;

final class Classmap
{
    /** @var array<string, ClassmapItem> */
    private static array $classmap = [];

    public static function registerClass(
        FullClassName $className,
        string $pathname,
        string $classType,
        ?int $flag
    ) {
        if (!isset(Classmap::$classmap[$className->getFQCN()])) {
            Classmap::$classmap[$className->getFQCN()] = new ClassmapItem($pathname, $classType, $flag);
        }
    }

    public static function registerClassImplements(
        FullClassName $classImplementing,
        FullClassName $classImplemented,
        int $startLine,
        int $endLine
    ) {
        Classmap::$classmap[$classImplementing->getFQCN()]->addInterface(
            new ClassmapRelation($classImplemented, $startLine, $endLine)
        );
    }

    public static function registerClassExtends(
        FullClassName $classExtending,
        FullClassName $classExtended,
        int $startLine,
        int $endLine
    ) {
        Classmap::$classmap[$classExtending->getFQCN()]->addParent(
            new ClassmapRelation($classExtended, $startLine, $endLine)
        );
    }

    public static function registerClassIncludesTrait(
        FullClassName $classIncluding,
        FullClassName $classIncluded,
        int $startLine,
        int $endLine
    ) {
        Classmap::$classmap[$classIncluding->getFQCN()]->addTrait(
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

        Classmap::$classmap[$classDepending->getFQCN()]->addDependency(
            new ClassmapRelation($classDepended, $startLine, $endLine)
        );
    }

    /*
     * @return array<string, SrcNode>
     */
    public static function getClassmap(): array
    {
        return Classmap::translateClassmap(Classmap::$classmap);
    }

    /**
     * Temporary BC structure
     * @param array<string, ClassmapItem> $classmap
     * @return array<string, SrcNode>
     */
    private static function translateClassmap(array $classmap): array
    {
        foreach ($classmap as $className => $properties) {
            $srcNodes[$className] = new SrcNode(
                $properties->getPathname(),
                FullClassName::createFromFQCN($className),
                array_merge(
                    Classmap::addRelations(Dependency::class, $properties->getDependencies()),
                    Classmap::addRelations(
                        Inheritance::class,
                        $properties->getParent() === null ? [] : [$properties->getParent()]
                    ),
                    Classmap::addRelations(Composition::class, $properties->getInterfaces()),
                    Classmap::addRelations(Mixin::class, $properties->getTraits())
                )
            );
        }

        return $srcNodes ?? [];
    }

    /**
     * @param array<ClassmapRelation> $classmapRelations
     * @return array<AbstractRelation>
     */
    private static function addRelations(string $type, array $classmapRelations): array
    {
        foreach ($classmapRelations as $relation) {
            $result[] = new $type($relation->relatedClass, $relation->startLine, $relation->endLine);
        }

        return $result ?? [];
    }
}
