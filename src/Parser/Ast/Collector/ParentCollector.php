<?php

namespace PhpAT\Parser\Ast\Collector;

use PhpAT\Parser\Ast\Classmap;
use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Ast\Traverser\TraverseContext;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class ParentCollector extends NodeVisitorAbstract
{
    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Stmt\ClassLike && $node->name !== null) {
            if (isset($node->extends) && $node->extends instanceof Node\Name\FullyQualified) {
                Classmap::registerClassExtends(
                    TraverseContext::className(),
                    FullClassName::createFromFQCN($node->extends->toString())
                );
            }
        }

        return $node;
    }
}
