<?php

namespace PhpAT\Parser;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class TraitCollector extends NodeVisitorAbstract
{
    /**
     * @var array
     */
    private $traits = [];

    public function beforeTraverse(array $nodes)
    {
        $this->traits = [];
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Stmt\TraitUse) {
            foreach ($node->traits as $trait) {
                if ($trait instanceof Node\Name\FullyQualified) {
                    $this->traits[] = ClassName::createFromFQCN($trait->toString());
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getTraits(): array
    {
        return $this->traits;
    }
}
