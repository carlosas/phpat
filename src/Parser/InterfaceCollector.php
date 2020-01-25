<?php

namespace PhpAT\Parser;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class InterfaceCollector extends NodeVisitorAbstract
{
    /**
     * @var array
     */
    private $interfaces = [];

    public function beforeTraverse(array $nodes)
    {
        $this->interfaces = [];
    }

    public function leaveNode(Node $node)
    {
        if (!is_null($node->implements ?? null) && !empty($node->implements)) {
            foreach ($node->implements as $implements) {
                if ($implements instanceof Node\Name\FullyQualified) {
                    $this->interfaces[] = ClassName::createFromFQCN($implements->toString());
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getInterfaces(): array
    {
        return $this->interfaces;
    }
}
