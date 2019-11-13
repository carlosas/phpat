<?php declare(strict_types=1);

namespace PhpAT\Parser\Collector;

use PhpAT\Parser\ClassName;
use PhpParser\Node;

class ClassNameCollector extends AbstractCollector
{
    public function leaveNode(Node $node): void
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
