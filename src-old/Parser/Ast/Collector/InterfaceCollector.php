<?php

namespace PHPatOld\Parser\Ast\Collector;

use PHPatOld\Parser\Ast\Classmap\Classmap;
use PHPatOld\Parser\Ast\FullClassName;
use PHPatOld\Parser\Ast\Traverser\TraverseContext;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class InterfaceCollector extends NodeVisitorAbstract
{
    public function leaveNode(Node $node)
    {
        if (
            $node instanceof Node\Stmt\ClassLike
            && $node->name !== null
            && (isset($node->implements) && $node->implements !== null)
        ) {
            foreach ($node->implements as $implements) {
                if ($implements instanceof Node\Name\FullyQualified) {
                    Classmap::registerClassImplements(
                        TraverseContext::className(),
                        FullClassName::createFromFQCN($implements->toString()),
                        $implements->getStartLine(),
                        $implements->getEndLine()
                    );
                }
            }
        }

        return $node;
    }
}
