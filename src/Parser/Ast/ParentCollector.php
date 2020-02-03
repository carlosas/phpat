<?php

namespace PhpAT\Parser\Ast;

use PhpAT\Parser\ClassName;
use PhpAT\Parser\Relation\Inheritance;
use PhpParser\Node;

class ParentCollector extends AbstractRelationCollector
{
    public function leaveNode(Node $node)
    {
        if (isset($node->extends) && $node->extends instanceof Node\Name\FullyQualified) {
            $this->results[] = new Inheritance($node->getLine(), ClassName::createFromFQCN($node->extends->toString()));
        }
    }
}
