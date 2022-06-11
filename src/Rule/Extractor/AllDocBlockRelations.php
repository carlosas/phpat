<?php

declare(strict_types=1);

namespace PHPat\Rule\Extractor;

use PhpParser\Node;
use PHPStan\Analyser\Scope;

trait AllDocBlockRelations
{
    public function getNodeType(): string
    {
        return Node::class;
    }

    /**
     * @param Node $node
     * @return array<int, mixed>
     */
    protected function extractNodeClassNames(Node $node, Scope $scope): array
    {
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
            $classReflection ? $classReflection->getName() : null,
            $traitReflection ? $traitReflection->getName() : null,
            $functionReflection ? $functionReflection->getName() : null,
            $docComment->getText()
        );

        $names = [];
        $tags  = array_filter(
            array_merge(
                $resolvedPhpDoc->getVarTags(),
                $resolvedPhpDoc->getMethodTags(),
                $resolvedPhpDoc->getPropertyTags(),
                $resolvedPhpDoc->getTemplateTags(),
                $resolvedPhpDoc->getExtendsTags(),
                $resolvedPhpDoc->getImplementsTags(),
                $resolvedPhpDoc->getUsesTags(),
                $resolvedPhpDoc->getParamTags(),
                [$resolvedPhpDoc->getReturnTag()],
                [$resolvedPhpDoc->getThrowsTag()],
                $resolvedPhpDoc->getMixinTags(),
                $resolvedPhpDoc->getTypeAliasTags(),
                $resolvedPhpDoc->getTypeAliasImportTags(),
                [$resolvedPhpDoc->getDeprecatedTag()]
            )
        );
        foreach ($tags as $tag) {
            if (method_exists($tag, 'getType')) {
                array_push($names, ...$tag->getType()->getReferencedClasses());
            }
        }

        return $names;
    }
}
