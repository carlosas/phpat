<?php

declare(strict_types=1);

namespace PHPat\Rule\Extractor\Relation;

use PhpParser\Node;
use PHPStan\Analyser\Scope;

trait AttributeExtractor
{
    public function getNodeType(): string
    {
        return Node\Attribute::class;
    }

    /**
     * @param Node\Attribute $node
     * @return list<class-string>
     */
    protected function extractNodeClassNames(Node $node, Scope $scope): array
    {
        return [nameToClassString($node->name)];
    }
}
