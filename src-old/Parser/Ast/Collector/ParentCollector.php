<?php

namespace PHPatOld\Parser\Ast\Collector;

use PHPatOld\Parser\Ast\Classmap\Classmap;
use PHPatOld\Parser\Ast\FullClassName;
use PHPatOld\Parser\Ast\Traverser\TraverseContext;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class ParentCollector extends NodeVisitorAbstract
{
    public function leaveNode(Node $node)
    {
        if (
            $node instanceof Node\Stmt\ClassLike
            && $node->name !== null
            && isset($node->extends)
            && $node->extends instanceof Node\Name\FullyQualified
        ) {
            Classmap::registerClassExtends(
                TraverseContext::className(),
                FullClassName::createFromFQCN($node->extends->toString()),
                $node->extends->getStartLine(),
                $node->extends->getEndLine()
            );
        }

        return $node;
    }
}
