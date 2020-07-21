<?php

namespace PhpAT\Parser\Ast\Extractor;

use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Ast\PhpType;
use PhpAT\Parser\Relation\AbstractRelation;
use phpDocumentor\Reflection\Types\Context;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflection\ReflectionType;

abstract class AbstractExtractor
{
    protected $relations = [];

    /**
     * @param ReflectionClass $class
     * @return AbstractRelation[]
     */
    abstract public function extract(ReflectionClass $class): array;

    protected function isClassType(?ReflectionType $type): bool
    {
        if (is_null($type) || PhpType::isBuiltinType($type->getName())) {
            return false;
        }

        return true;
    }

    protected function addRelation(string $type, int $line, FullClassName $className)
    {
        $this->relations[] = new $type($line, $className);
    }

    /**
     * @return AbstractRelation[]
     */
    protected function removeDuplicates(): array
    {
        $found = [];
        foreach ($this->relations as $key => $relation) {
            $c = get_class($relation);
            if (($found[$relation->relatedClass->getName()] ?? null) instanceof $c) {
                unset($this->relations[$key]);
                continue;
            }

            $found[$relation->relatedClass->getName()] = $relation;
        }

        return $found;
    }

    /**
     * @return AbstractRelation[]
     */
    protected function flushRelations(): array
    {
        $result = $this->relations ?? [];
        $this->relations = [];

        return $result;
    }
}
