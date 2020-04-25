<?php

namespace PhpAT\Parser\Ast\Collector;

use PhpAT\Parser\FullClassName;
use PhpAT\Parser\Relation\Inheritance;
use PhpParser\Node;

class ParentCollector extends AbstractRelationCollector
{
    public function leaveNode(Node $node)
    {
        if (
            isset($node->namespacedName)
            && isset($node->extends)
            && $node->extends instanceof Node\Name\FullyQualified
        ) {
            $this->results[] = new Inheritance(
                $node->getLine(),
                FullClassName::createFromFQCN($node->extends->toString())
            );
        }

        return $node;
    }
}
