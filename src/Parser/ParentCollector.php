<?php

namespace PhpAT\Parser;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class ParentCollector extends NodeVisitorAbstract
{
    private $previousNode;
    /**
     * @var ClassName
     */
    private $parent = null;

    public function beforeTraverse(array $nodes)
    {
        $this->previousNode = null;
        $this->parent = null;
    }

    public function leaveNode(Node $node)
    {
        if (!is_null($node->extends ?? null) && !empty($node->extends)) {
            if ($node->extends instanceof Node\Name\FullyQualified) {
                $this->parent = ClassName::createFromFQCN($node->extends->toString());
            }
        }

        $this->previousNode = $node;
    }

    /**
     * @return ClassName|null
     */
    public function getParent(): ?ClassName
    {
        return $this->parent;
    }
}
