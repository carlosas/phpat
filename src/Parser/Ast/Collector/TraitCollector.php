<?php

namespace PhpAT\Parser\Ast\Collector;

use PhpAT\Parser\Ast\Classmap;
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
//                echo TraverseContext::className()->toString() . PHP_EOL;
//                echo $trait->toString() . PHP_EOL;
//                echo '---------------------------------------------------' . PHP_EOL; //TODO: y los traits fuera de la clase?
                if ($trait instanceof Node\Name\FullyQualified) {
                    Classmap::registerClassIncludesTrait(
                        TraverseContext::className(),
                        FullClassName::createFromFQCN($trait->toString())
                    );
                }
            }
        }

        return $node;
    }
}
