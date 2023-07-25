<?php

declare(strict_types=1);

namespace PHPat\Rule\Extractor\Declaration;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\PropertyAssignNode;

trait PropertyAssignedOutOfConstructorExtractor
{
    public function getNodeType(): string
    {
        return PropertyAssignNode::class;
    }

    /**
     * @param PropertyAssignNode $node
     */
    protected function meetsDeclaration(Node $node, Scope $scope): bool
    {
        $functionReflection = $scope->getFunction();
        if ($functionReflection === null || $functionReflection->getName() === '__construct') {
            return true;
        }

        return false;
    }
}
