<?php

namespace PHPat\Rule\Traits;

use PhpParser\Node;
use PHPStan\Analyser\Scope;

trait FullyQualifiedNode
{
    public function getNodeType(): string
    {
        return Node\Name\FullyQualified::class;
    }

    /**
     * @param Node\Name\FullyQualified $node
     */
    protected function extractTargetClassName(Node $node, Scope $scope): ?string
    {
        return $node instanceof Node\Name\FullyQualified ? $node->toString() : $scope->resolveName($node);
    }
}
