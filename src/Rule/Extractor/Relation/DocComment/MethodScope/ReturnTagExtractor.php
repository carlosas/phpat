<?php declare(strict_types=1);

namespace PHPat\Rule\Extractor\Relation\DocComment\MethodScope;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassMethodNode;
use PHPStan\PhpDoc\Tag\ReturnTag;
use PHPStan\ShouldNotHappenException;

trait ReturnTagExtractor
{
    public function getNodeType(): string
    {
        return InClassMethodNode::class;
    }

    /**
     * @param  InClassMethodNode        $node
     * @return array<int, mixed>
     * @throws ShouldNotHappenException
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
        $traitReflection = $scope->getTraitReflection();
        $functionReflection = $scope->getFunction();

        $resolvedPhpDoc = $this->fileTypeMapper->getResolvedPhpDoc(
            $scope->getFile(),
            $classReflection->getName(),
            $traitReflection ? $traitReflection->getName() : null,
            $functionReflection ? $functionReflection->getName() : null,
            $docComment->getText()
        );

        $names = [];
        $tag = $resolvedPhpDoc->getReturnTag();

        if ($tag instanceof ReturnTag) {
            array_push($names, ...$tag->getType()->getReferencedClasses());
        }

        return $names;
    }
}
