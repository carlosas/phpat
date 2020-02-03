<?php

namespace PhpAT\Parser\Ast;

use PhpAT\Parser\FullClassName;
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
    }

    public function leaveNode(Node $node)
    {
        if (isset($node->implements) && !empty($node->implements)) {
            foreach ($node->implements as $implements) {
                if ($implements instanceof Node\Name\FullyQualified) {
                    $this->addInterface($node->getLine(), $implements->toString());
                }
            }
        }
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
