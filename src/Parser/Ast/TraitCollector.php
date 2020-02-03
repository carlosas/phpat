<?php

namespace PhpAT\Parser\Ast;

use PhpAT\Parser\FullClassName;
use PhpAT\Parser\Relation\Mixin;
use PhpParser\Node;

class TraitCollector extends AbstractRelationCollector
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
        if ($node instanceof Node\Stmt\TraitUse) {
            foreach ($node->traits as $trait) {
                if ($trait instanceof Node\Name\FullyQualified) {
                    $this->addTrait($node->getLine(), $trait->toString());
                }
            }
        }
    }

    private function addTrait(int $line, string $fqcn): void
    {
        if (!array_key_exists($fqcn, $this->found)) {
            $class = FullClassName::createFromFQCN($fqcn);
            $this->found[$fqcn] = $class->getFQCN();
            $this->results[] = new Mixin($line, $class);
        }
    }
}
