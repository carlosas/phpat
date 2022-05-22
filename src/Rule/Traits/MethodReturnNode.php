<?php

namespace PHPat\Rule\Traits;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;

trait MethodReturnNode
{
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * @param ClassMethod $node
     */
    protected function extractTargetClassName(Node $node, Scope $scope): ?string
    {
        if (!($node->getReturnType() instanceof Node\Name)) {
            return null;
        }

        return $node->getReturnType()->toString();
    }
}
