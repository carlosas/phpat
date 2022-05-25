<?php

namespace PHPat\Rule\Traits;

use PHPat\Parser\TypeNodeParser;
use PhpParser\Node;
use PHPStan\Analyser\Scope;

trait ClassConstantNode
{
    public function getNodeType(): string
    {
        return Node\Expr\ClassConstFetch::class;
    }

    /**
     * @param Node\Expr\ClassConstFetch $node
     * @return iterable<class-string>
     */
    protected function extractTargetClassNames(Node $node, Scope $scope): iterable
    {
        return namesToClassStrings(TypeNodeParser::parse($node->class, $scope));
    }
}
