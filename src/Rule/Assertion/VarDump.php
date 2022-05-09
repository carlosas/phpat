<?php

namespace PhpAT\Rule\Assertion;

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
        return [];
        //return [get_class($node)];
    }
}
