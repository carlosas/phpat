<?php

namespace PhpAT\Parser\Ast\Extractor;

use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Ast\Type\PhpType;
use PhpAT\Parser\Relation\AbstractRelation;
use PHPStan\BetterReflection\Reflection\ReflectionClass;
use PHPStan\BetterReflection\Reflection\ReflectionType;

abstract class AbstractExtractor
{
    protected $relations = [];

    /**
     * @param ReflectionClass $class
     * @return AbstractRelation[]
     */
    abstract public function extract(ReflectionClass $class): array;

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
