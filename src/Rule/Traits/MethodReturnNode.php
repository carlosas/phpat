<?php

namespace PHPat\Rule\Traits;

use PHPat\Parser\ComplexTypeParser;
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
        if (!(
            $node->getReturnType() instanceof Node\Name
            || $node->getReturnType() instanceof Node\ComplexType
        )) {
            return [];
        }

        if ($node->getReturnType() instanceof Node\ComplexType) {
            return ComplexTypeParser::parse($node->getReturnType());
        }

        return [$node->getReturnType()->toString()];
    }
}
