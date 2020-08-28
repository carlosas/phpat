<?php

namespace PhpAT\Parser\Ast\Extractor;

use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Ast\PhpType;
use PhpAT\Parser\Relation\AbstractRelation;
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
        if ($type === null || PhpType::isBuiltinType($type->getName())) {
            return false;
        }

        return true;
    }

    protected function addRelation(string $type, int $line, FullClassName $className)
    {
        $this->relations[$className->getFQCN()]['line-' . $line] = new $type($line, $className);
    }

    /**
     * @return AbstractRelation[]
     */
    protected function flushRelations(): array
    {
        foreach ($this->relations ?? [] as $relation) {
            foreach ($relation as $occurrence) {
                $result[] = $occurrence;
            }
        }

        $this->relations = [];

        return $result ?? [];
    }
}
