<?php

namespace PHPat\Rule\Extractor;

use PHPat\Parser\TypeNodeParser;
use PhpParser\Node;
use PHPStan\Analyser\Scope;

trait ClassPropertyExtractor
{
    public function getNodeType(): string
    {
        return Node\Stmt\Property::class;
    }

    /**
     * @param Node\Stmt\Property $node
     * @return iterable<class-string>
     */
    protected function extractNodeClassNames(Node $node, Scope $scope): iterable
    {
        return namesToClassStrings(TypeNodeParser::parse($node->type, $scope));
    }
}
