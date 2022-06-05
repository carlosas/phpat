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
     * @return iterable<class-string>
     */
    protected function extractNodeClassNames(Node $node, Scope $scope): iterable
    {
        if (!$scope->isInClass()) {
            return [];
        }

        $docComment = $node->getDocComment();
        if ($docComment === null) {
            return [];
        }

        $resolvedPhpDoc = $this->fileTypeMapper->getResolvedPhpDoc(
            $scope->getFile(),
            $scope->getClassReflection()->getName(),
            $scope->isInTrait() ? $scope->getTraitReflection()->getName() : null,
            $scope->getFunction() !== null ? $scope->getFunction()->getName() : null,
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
            array_push($names, ...$tag->getType()->getReferencedClasses());
        }

        return $names;
    }
}
