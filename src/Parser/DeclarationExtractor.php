<?php

namespace PhpAT\Parser;

use PhpParser\Node;
use PhpParser\Node\Stmt\DeclareDeclare;

class DeclarationExtractor extends AbstractExtractor
{
    private $declarations = [];

    public function leaveNode(Node $node)
    {
        if ($node instanceof DeclareDeclare) {
            $this->declarations[$node->key->name] = $node->value->value;
        }

        $this->result = [];
        foreach ($this->declarations as $decKey => $decValue) {
            $this->result[] = new Declaration($decKey, $decValue);
        }
    }
}
