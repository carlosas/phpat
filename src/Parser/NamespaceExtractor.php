<?php

namespace PhpAT\Parser;

use PhpParser\Node;

//TODO: change class parent to FindingVisitor

class NamespaceExtractor extends AbstractExtractor
{
    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Stmt\Namespace_) {
            foreach ($node->stmts as $stmt) {
                if (isset($stmt->name) && isset($stmt->name->name)) {
                    $namespace = implode('\\', $node->name->parts);
                    $classname = '\\' . $stmt->name->name;

                    $this->result[] = $namespace . $classname;
                }
            }
        }
    }
}
