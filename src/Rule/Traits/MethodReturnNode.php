<?php

namespace PHPat\Rule\Traits;

use PHPat\Parser\TypeNodeParser;
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
     * @return iterable<class-string>
     */
    protected function extractTargetClassNames(Node $node, Scope $scope): iterable
    {
        return namesToClassStrings(TypeNodeParser::parse($node->getReturnType(), $scope));
    }
}
