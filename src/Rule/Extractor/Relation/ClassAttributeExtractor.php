<?php declare(strict_types=1);

namespace PHPat\Rule\Extractor\Relation;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;

trait ClassAttributeExtractor
{
    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    /**
     * @param  InClassNode        $node
     * @return list<class-string>
     */
    protected function extractNodeClassNames(Node $node, Scope $scope): array
    {
        $fn = static fn ($a) => $a->getName();

        return array_map($fn, $node->getClassReflection()->getNativeReflection()->getAttributes());
    }
}
