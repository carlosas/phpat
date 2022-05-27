<?php

namespace PHPat\Rule\Assertion;

use PHPat\SimpleClass;
use PhpParser\Node;
use PHPStan\Analyser\Scope;

class VarDump implements \PHPStan\Rules\Rule
{
    public function getNodeType(): string
    {
        return \PhpParser\Node::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        /*if ($scope->isInClass() && $scope->getClassReflection()->getName() === SimpleClass::class) {
            return [get_class($node)];
        }*/
        return [];
    }
}
