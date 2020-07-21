<?php

namespace PhpAT\Parser\Ast;

class NodeTraverser extends \PhpParser\NodeTraverser
{
    public function traverse(array $nodes) : array
    {
        $result = parent::traverse($nodes);
        $this->reset();

        return $result;
    }

    public function reset(): void
    {
        foreach ($this->visitors as $visitor) {
            $this->removeVisitor($visitor);
        }
    }
}
