<?php

namespace PhpAT\Parser;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class ParentCollector extends NodeVisitorAbstract
{
    /**
     * @var ClassName|null
     */
    private $parent = null;

    public function beforeTraverse(array $nodes)
    {
        $this->parent = null;
    }

    public function leaveNode(Node $node)
    {
        if (isset($node->extends) && $node->extends instanceof Node\Name\FullyQualified) {
            $this->parent = ClassName::createFromFQCN($node->extends->toString());
        }
    }

    /**
     * @return ClassName|null
     */
    public function getParent(): ?ClassName
    {
        return $this->parent;
    }
}
