<?php declare(strict_types=1);

namespace PHPat\Rule\Extractor\Relation;

use PhpParser\Node;
use PHPStan\Analyser\Scope;

trait AllAttributesExtractor
{
    public function getNodeType(): string
    {
        return Node\AttributeGroup::class;
    }

    /**
     * @param  Node\AttributeGroup $node
     * @return list<class-string>
     */
    protected function extractNodeClassNames(Node $node, Scope $scope): array
    {
        if (!$scope->isInClass()) {
            return [];
        }

        $fn = static fn ($a) => $a->name->toString();

        return array_map($fn, $node->attrs);
    }
}
