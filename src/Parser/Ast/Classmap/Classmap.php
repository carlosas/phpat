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

    public static function registerClassImplements(FullClassName $classImplementing, FullClassName $classImplemented)
    {
        static::$classmap[$classImplementing->getFQCN()]->addInterface($classImplemented);
    }

    public static function registerClassExtends(FullClassName $classExtending, FullClassName $classExtended)
    {
        static::$classmap[$classExtending->getFQCN()]->addParent($classExtended);
    }

    public static function registerClassIncludesTrait(FullClassName $classUsing, FullClassName $classUsed)
    {
        static::$classmap[$classUsing->getFQCN()]->addTrait($classUsed);
    }

    public static function registerClassDepends(FullClassName $classDepending, FullClassName $classDepended)
    {
        if (PhpType::isBuiltinType($classDepended->getFQCN()) || PhpType::isSpecialType($classDepended->getFQCN())) {
            return;
        }

        static::$classmap[$classDepending->getFQCN()]->addDependency($classDepended);
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
     * @param FullClassName[] $className
     * @return AbstractRelation[]
     */
    private static function addRelations(string $type, array $className): array
    {
        foreach ($className as $name) {
            $result[] = new $type(0, $name);
        }

        return $result ?? [];
    }
}
