<?php

namespace PhpAT\Parser;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class TraitCollector extends NodeVisitorAbstract
{
    /**
     * @return ClassName[]
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
                    $this->addTrait($trait->toString());
                }
            }
        }
    }

    /**
     * @return ClassName[]
     */
    public function getTraits(): array
    {
        return $this->traits;
    }

    private function addTrait(string $fqcn): void
    {
        if (!isset($this->traits[$fqcn])) {
            $this->traits[$fqcn] = ClassName::createFromFQCN($fqcn);
        }
    }
}
