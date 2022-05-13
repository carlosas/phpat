<?php

namespace PhpAT\Rule\Assertion\Dependency\MustNotDepend;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\ClassPropertiesNode;
use PHPStan\Rules\Rule as PHPStanRule;

/**
 * @implements PHPStanRule<ClassPropertiesNode>
 */
class MethodParamRule extends MustNotDepend implements PHPStanRule
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
