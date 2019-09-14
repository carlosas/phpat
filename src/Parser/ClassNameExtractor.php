<?php

namespace PhpAT\Parser;

use PhpParser\Node;

class ClassNameExtractor extends AbstractExtractor
{
    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Stmt\Namespace_) {
            foreach ($node->stmts as $stmt) {
                if (isset($stmt->name) && isset($stmt->name->name)) {
                    $this->result[] = new ClassName($node->name->toString(), $stmt->name->name);
                }
            }
        }
    }
}
