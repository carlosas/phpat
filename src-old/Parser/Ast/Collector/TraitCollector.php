<?php

namespace PHPatOld\Parser\Ast\Collector;

use PHPatOld\Parser\Ast\Classmap\Classmap;
use PHPatOld\Parser\Ast\FullClassName;
use PHPatOld\Parser\Ast\Traverser\TraverseContext;
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
                        FullClassName::createFromFQCN($trait->toString()),
                        $trait->getStartLine(),
                        $trait->getEndLine()
                    );
                }
            }
        }

        return $node;
    }
}
