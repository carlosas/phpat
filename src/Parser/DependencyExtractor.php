<?php

namespace PhpAT\Parser;

use PhpParser\Node;
use PhpParser\Node\Stmt\UseUse;
use PhpParser\Node\Name\FullyQualified;

class DependencyExtractor extends AbstractExtractor
{
    private $dependencies = [];

    public function leaveNode(Node $node)
    {
        if ($node instanceof UseUse) {
            $this->dependencies[] = $node->name->toString();
        } elseif ($node instanceof FullyQualified) {
            $this->dependencies[] = $node->toString();
        }
        //TODO: This visitor includes composition/inheritance from different namespaces only
        //TODO: Docblock dependencies

        $this->dependencies = array_unique($this->dependencies);
        $this->result = [];
        foreach ($this->dependencies as $dep) {
            $this->result[] = ClassName::createFromFQDN($dep);
        }
    }
}
