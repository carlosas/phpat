<?php

namespace PHPat\Rule\Traits;

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
     */
    protected function extractTargetClassName(Node $node, Scope $scope): ?string
    {
        if (!($node->class instanceof Node\Name)) {
            return null;
        }

        return $node->class->toString();
    }
}
