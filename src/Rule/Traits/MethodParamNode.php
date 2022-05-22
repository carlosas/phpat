<?php

namespace PHPat\Rule\Traits;

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
        if (!$node->type instanceof Node\Name) {
            return [];
        }

        return [$node->type->toString()];
    }
}
