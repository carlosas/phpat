<?php

namespace PHPat\Rule\Traits;

use PHPat\Parser\TypeNodeParser;
use PhpParser\Node;
use PHPStan\Analyser\Scope;

trait NewNode
{
    public function getNodeType(): string
    {
        return Node\Expr\New_::class;
    }

    /**
     * @param Node\Expr\New_ $node
     * @return iterable<class-string>
     */
    protected function extractTargetClassNames(Node $node, Scope $scope): iterable
    {
        return namesToClassStrings(TypeNodeParser::parse($node->class, $scope));
    }
}
