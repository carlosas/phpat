<?php

namespace PhpAT\Parser;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class NameCollector extends NodeVisitorAbstract
{
    /**
     * @var ClassName
     */
    private $name = null;

    public function beforeTraverse(array $nodes)
    {
        $this->name = null;
    }

    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Stmt\ClassLike) {
            $this->name = ClassName::createFromFQCN($node->namespacedName->toString());
        }
    }

    /**
     * @return ClassName|null
     */
    public function getName(): ?ClassName
    {
        return $this->name;
    }
    /**
     * @return string|null
     */
    public function getNameString(): ?string
    {
        return is_null($this->getName()) ? null : $this->getName()->getFQCN();
    }
}
