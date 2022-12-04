<?php

declare(strict_types=1);

namespace PHPat\Rule\Extractor\Relation\DocComment\MethodScope;

use PhpParser\Node;
use PHPStan\Analyser\Scope;

trait VarTagExtractor
{
    public function getNodeType(): string
    {
        return Node\Expr\Variable::class;
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
            $classReflection->getName(),
            $traitReflection ? $traitReflection->getName() : null,
            $functionReflection ? $functionReflection->getName() : null,
            $docComment->getText()
        );

        $names = [];
        foreach (array_filter($resolvedPhpDoc->getVarTags()) as $tag) {
            array_push($names, ...$tag->getType()->getReferencedClasses());
        }

        return $names;
    }
}
