<?php

namespace PhpAT\Parser\Ast\Collector;

use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Relation\Composition;
use PhpParser\Node;

class InterfaceCollector extends AbstractRelationCollector
{
    /**
     * @var string[]
     */
    private $found = [];

    public function beforeTraverse(array $nodes)
    {
        parent::beforeTraverse($nodes);
        $this->found = [];

        return $nodes;
    }

    public function leaveNode(Node $node)
    {
        if (isset($node->namespacedName) && isset($node->implements) && !empty($node->implements)) {
            foreach ($node->implements as $implements) {
                if ($implements instanceof Node\Name\FullyQualified) {
                    $this->addInterface($node->getLine(), $implements->toString());
                }
            }
        }

        return $node;
    }

    private function addInterface(int $line, string $fqcn): void
    {
        if (!array_key_exists($fqcn, $this->found)) {
            $class = FullClassName::createFromFQCN($fqcn);
            $this->found[$fqcn] = $class->getFQCN();
            $this->results[] = new Composition($line, $class);
        }
    }
}
