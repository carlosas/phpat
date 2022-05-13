<?php

namespace PhpAT\Rule\Assertion\Dependency\MustNotDepend;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule as PHPStanRule;

/**
 * @implements PHPStanRule<Node\Expr\ClassConstFetch>
 */
class ClassConstantRule extends MustNotDepend implements PHPStanRule
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
