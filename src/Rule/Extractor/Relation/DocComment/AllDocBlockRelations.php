<?php

declare(strict_types=1);

namespace PHPat\Rule\Extractor\Relation\DocComment;

use PhpParser\Node;
use PHPStan\Analyser\Scope;

trait AllDocBlockRelations
{
    /** @var array<string, array<int, bool>> */
    protected $commentMap = [];

    public function getNodeType(): string
    {
        return Node::class;
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

        if (isset($this->commentMap[$classReflectionName][$docComment->getStartLine()])) {
            return [];
        }

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
        $tags  = array_filter(
            array_merge(
                $resolvedPhpDoc->getMethodTags(),
                $resolvedPhpDoc->getPropertyTags(),
                $resolvedPhpDoc->getTemplateTags(),
                $resolvedPhpDoc->getExtendsTags(),
                $resolvedPhpDoc->getImplementsTags(),
                $resolvedPhpDoc->getUsesTags(),
                [$resolvedPhpDoc->getReturnTag()],
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
            if (method_exists($tag, 'getReturnType')) {
                array_push($names, ...$tag->getReturnType()->getReferencedClasses());
            }
            if (method_exists($tag, 'getParameters')) {
                foreach ($tag->getParameters() as $parameter) {
                    array_push($names, ...$parameter->getType()->getReferencedClasses());
                }
            }
        }
        if (isset($this->commentMap[$classReflectionName])) {
            $this->commentMap[$classReflectionName][$docComment->getStartLine()] = true;
        } else {
            $this->commentMap[$classReflectionName] = [$docComment->getStartLine() => true];
        }

        return $names;
    }
}
