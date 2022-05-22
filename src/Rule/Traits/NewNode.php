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
     */
    protected function extractTargetClassName(Node $node, Scope $scope): ?string
    {
        if (!($node->class instanceof Node\Name)) {
            return null;
        }

        return $node->class->toString();
    }
}
