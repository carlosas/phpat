<?php

namespace PhpAT\Parser\Ast\Collector;

use PhpAT\Parser\Relation\AbstractRelation;
use PhpParser\NodeVisitorAbstract;

abstract class AbstractRelationCollector extends NodeVisitorAbstract
{
    /** @var AbstractRelation[] */
    protected $results = [];

    public function beforeTraverse(array $nodes)
    {
        $this->results = [];

        return $nodes;
    }

    /**
     * @return AbstractRelation[]
     */
    final public function getResults(): array
    {
        return $this->results;
    }
}
