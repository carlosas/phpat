<?php

namespace PhpAT\Parser\Ast\Collector;

use PhpAT\Parser\Ast\Classmap;
use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Ast\Traverser\TraverseContext;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class InterfaceCollector extends NodeVisitorAbstract
{
    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Stmt\ClassLike && $node->name !== NULL) {
            if (isset($node->implements)) {
                foreach ($node->implements as $implements) {
                    if ($implements instanceof Node\Name\FullyQualified) {
                        Classmap::registerClassImplements(
                            TraverseContext::className(),
                            FullClassName::createFromFQCN($implements->toString())
                        );
                    }
                }
            }
        }

        return $node;
    }
}
