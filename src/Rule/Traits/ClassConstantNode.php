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
     * @return iterable<class-string>
     */
    protected function extractTargetClassNames(Node $node, Scope $scope): iterable
    {
        if (!($node->class instanceof Node\Name)) {
            return [];
        }

        return [$node->class->toString()];
    }
}
