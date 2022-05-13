<?php

namespace PhpAT\Rule\Assertion\Dependency\MustNotDepend;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule as PHPStanRule;

/**
 * @implements PHPStanRule<Node\Expr\New_>
 */
class NewRule extends MustNotDepend implements PHPStanRule
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
