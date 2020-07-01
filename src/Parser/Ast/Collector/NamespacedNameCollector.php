<?php

namespace PhpAT\Parser\Ast\Collector;

use PhpAT\Parser\Ast\FullClassName;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class NamespacedNameCollector extends NodeVisitorAbstract
{
    /** @var FullClassName|null */
    private $name = null;

    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Stmt\ClassLike && isset($node->namespacedName)) {
            $this->name = FullClassName::createFromFQCN($node->namespacedName->toString());
        }

        return $node;
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
