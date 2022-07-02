<?php

declare(strict_types=1);

namespace PHPat\Rule\Assertion;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node>
 */
class VarDump implements Rule
{
    public function getNodeType(): string
    {
        return Node::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        /*if ($scope->isInClass() && $scope->getClassReflection()->getName() === SimpleClass::class) {
            return [get_class($node)];
        }*/
        return [];
    }
}
