<?php

declare(strict_types=1);

namespace PHPat\Rule\Extractor\Relation\DocComment\ClassScope;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;

trait MethodTagExtractor
{
    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    /**
     * @throws \PHPStan\ShouldNotHappenException
     * @return array<int, mixed>
     */
    protected function extractNodeClassNames(Node $node, Scope $scope): array
    {
        if ($this->configuration->ignoreDocComments()) {
            return [];
        }

        if (!$scope->isInClass()) {
            return [];
        }

        $docComment = $node->getDocComment();
        if ($docComment === null) {
            return [];
        }

        $classReflection    = $scope->getClassReflection();
        $traitReflection    = $scope->getTraitReflection();
        $functionReflection = $scope->getFunction();

        $resolvedPhpDoc = $this->fileTypeMapper->getResolvedPhpDoc(
            $scope->getFile(),
            $classReflection !== null ? $classReflection->getName() : null,
            $traitReflection ? $traitReflection->getName() : null,
            $functionReflection ? $functionReflection->getName() : null,
            $docComment->getText()
        );

        $names = [];
        foreach ($resolvedPhpDoc->getMethodTags() as $tag) {
            foreach ($tag->getParameters() as $parameter) {
                array_push($names, ...$parameter->getType()->getReferencedClasses());
            }
            array_push($names, ...$tag->getReturnType()->getReferencedClasses());
        }

        return $names;
    }
}
