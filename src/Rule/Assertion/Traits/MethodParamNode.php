<?php

namespace PHPat\Rule\Assertion\Traits;

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
     */
    protected function extractTargetClassName(Node $node, Scope $scope): ?string
    {
        if (!$node->type instanceof Node\Name) {
            return null;
        }

        return $node->type->toString();
    }
}
