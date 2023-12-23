<?php declare(strict_types=1);

namespace PHPat\Rule\Extractor\Declaration;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;

trait OnePublicMethodExtractor
{
    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    /**
     * @param InClassNode $node
     */
    protected function meetsDeclaration(Node $node, Scope $scope, array $params = []): bool
    {
        $reflectionClass = $node->getClassReflection()->getNativeReflection();
        $methods = $reflectionClass->getMethods(1);

        $methodsWithoutConstructor = array_filter(
            $methods,
            fn ($method) => $method->getName() !== '__construct'
        );

        return count($methodsWithoutConstructor) === 1;
    }
}
