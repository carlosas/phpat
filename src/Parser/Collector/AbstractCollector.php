<?php

namespace PhpAT\Parser\Collector;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class AbstractCollector extends NodeVisitorAbstract
{
    /**
     * @var array
     */
    protected $result = [];
    /**
     * @var Node
     */
    protected $previousNode;

    public function beforeTraverse(array $nodes) {
        $this->result = [];
    }

    public function getResult(): array
    {
        return $this->result;
    }

    public function leaveNode(Node $node)
    {
        $this->previousNode = $node;
    }
}
