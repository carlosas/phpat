<?php

namespace PhpAT\Parser\Ast;

class NodeTraverser extends \PhpParser\NodeTraverser
{
    public function reset(): void
    {
        foreach ($this->visitors as $visitor) {
            $this->removeVisitor($visitor);
        }
    }
}
