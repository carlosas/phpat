<?php declare(strict_types=1);

namespace PHPat\Rule\Extractor\Relation;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Reflection\ClassReflection;

trait AllParentsExtractor
{
    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    /**
     * @param  InClassNode         $node
     * @return array<class-string>
     */
    protected function extractNodeClassNames(Node $node, Scope $scope): array
    {
        return array_map(
            static fn (ClassReflection $c) => $c->getName(),
            $node->getClassReflection()->getParents()
        );
    }
}
