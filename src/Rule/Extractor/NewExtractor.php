<?php

declare(strict_types=1);

namespace PHPat\Rule\Extractor;

use PHPat\Parser\TypeNodeParser;
use PhpParser\Node;
use PHPStan\Analyser\Scope;

trait NewExtractor
{
    public function getNodeType(): string
    {
        return Node\Expr\New_::class;
    }

    /**
     * @param Node\Expr\New_ $node
     * @return iterable<class-string>
     */
    protected function extractNodeClassNames(Node $node, Scope $scope): iterable
    {
        return namesToClassStrings(TypeNodeParser::parse($node->class, $scope));
    }
}
