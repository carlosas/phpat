<?php

namespace PHPat\Rule\Traits;

use PHPat\Parser\TypeNodeParser;
use PhpParser\Node;
use PHPStan\Analyser\Scope;

trait MethodParamNode
{
    public function getNodeType(): string
    {
        return Node\Param::class;
    }

    /**
     * @param Node\Param $node
     * @return iterable<class-string>
     */
    protected function extractTargetClassNames(Node $node, Scope $scope): iterable
    {
        return namesToClassStrings(TypeNodeParser::parse($node->type, $scope));
    }
}
