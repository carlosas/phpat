<?php

namespace PhpAT\Parser\Ast\Collector;

use PhpAT\Parser\Ast\Classmap\Classmap;
use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Ast\Traverser\TraverseContext;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class TraitCollector extends NodeVisitorAbstract
{
    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\Stmt\TraitUse) {
            foreach ($node->traits as $trait) {
                if ($trait instanceof Node\Name\FullyQualified) {
                    Classmap::registerClassIncludesTrait(
                        TraverseContext::className(),
                        new FullClassName($trait->toString()),
                        $trait->getStartLine(),
                        $trait->getEndLine()
                    );
                }
            }
        }

        return $node;
    }
}
