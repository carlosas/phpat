<?php declare(strict_types=1);

namespace PHPat\Rule\Extractor\Declaration;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Reflection\MethodReflection;
use ReflectionClass;
use ReflectionMethod;

trait OnePublicMethodExtractor
{
    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    /**
     * @param InClassNode $node
     */
    protected function meetsDeclaration(Node $node, Scope $scope): bool
    {
        $reflectionClass = new ReflectionClass($node->getClassReflection()->getName());
        $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

        return count($methods) === 1;
    }
}
