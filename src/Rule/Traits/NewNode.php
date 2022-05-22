<?php

namespace PHPat\Rule\Traits;

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
        if (!($node->class instanceof Node\Name)) {
            return [];
        }

        return [$node->class->toString()];
    }
}
