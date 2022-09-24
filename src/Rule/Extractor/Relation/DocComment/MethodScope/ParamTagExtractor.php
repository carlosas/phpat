<?php

declare(strict_types=1);

namespace PHPat\Rule\Extractor\Relation\DocComment\MethodScope;

use PhpParser\Node;
use PHPStan\Analyser\Scope;

trait ParamTagExtractor
{
    public function getNodeType(): string
    {
        return Node\Stmt\ClassMethod::class;
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

        $classReflection = $scope->getClassReflection();
        if ($classReflection === null) {
            return [];
        }
        $classReflectionName = $classReflection->getName();

        $traitReflection    = $scope->getTraitReflection();
        $functionReflection = $scope->getFunction();

        $resolvedPhpDoc = $this->fileTypeMapper->getResolvedPhpDoc(
            $scope->getFile(),
            $classReflectionName,
            $traitReflection ? $traitReflection->getName() : null,
            $functionReflection ? $functionReflection->getName() : null,
            $docComment->getText()
        );

        $names = [];
        foreach ($resolvedPhpDoc->getParamTags() as $tag) {
            array_push($names, ...$tag->getType()->getReferencedClasses());
        }

        return $names;
    }
}
