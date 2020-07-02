<?php

namespace PhpAT\Parser\Ast\Collector;

use PhpAT\Parser\Ast\FullClassName;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class ClassNameCollector extends NodeVisitorAbstract
{
    /** @var FullClassName[] */
    private $names = [];

    public function leaveNode(Node $node)
    {
        if (
            (
                $node instanceof Node\Stmt\Class_
                || $node instanceof Node\Stmt\Interface_
                || $node instanceof Node\Stmt\Trait_
            )
            && isset($node->name->name)
            && is_string($node->name->name)
        ) {
            $this->names[] = FullClassName::createFromFQCN($node->name->name);
        }

        return $node;
    }

    /**
     * @return FullClassName[]
     */
    public function getNames(): array
    {
        return $this->names;
    }
}
