<?php

namespace PhpAT\Parser\Ast;

use PhpAT\Parser\FullClassName;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class NameCollector extends NodeVisitorAbstract
{
    /** @var FullClassName|null */
    private $name = null;

    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Stmt\ClassLike) {
            $this->name = FullClassName::createFromFQCN($node->namespacedName->toString());
        }
    }

    /**
     * @return FullClassName|null
     */
    public function getName(): ?FullClassName
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getNameString(): ?string
    {
        return ($this->getName() === null) ? null : $this->getName()->getFQCN();
    }
}
