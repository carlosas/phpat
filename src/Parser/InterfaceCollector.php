<?php

namespace PhpAT\Parser;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class InterfaceCollector extends NodeVisitorAbstract
{
    /**
     * @return ClassName[]
     */
    private $interfaces = [];

    public function beforeTraverse(array $nodes)
    {
        $this->interfaces = [];
    }

    public function leaveNode(Node $node)
    {
        if (isset($node->implements) && !empty($node->implements)) {
            foreach ($node->implements as $implements) {
                if ($implements instanceof Node\Name\FullyQualified) {
                    $this->addInterface($implements->toString());
                }
            }
        }
    }

    /**
     * @return ClassName[]
     */
    public function getInterfaces(): array
    {
        return $this->interfaces;
    }

    private function addInterface(string $fqcn): void
    {
        if (!isset($this->interfaces[$fqcn])) {
            $this->interfaces[$fqcn] = ClassName::createFromFQCN($fqcn);
        }
    }
}
