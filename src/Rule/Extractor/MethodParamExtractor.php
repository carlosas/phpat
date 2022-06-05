<?php

declare(strict_types=1);

namespace PHPat\Rule\Extractor;

use PHPat\Parser\TypeNodeParser;
use PhpParser\Node;
use PHPStan\Analyser\Scope;

trait MethodParamExtractor
{
    public function getNodeType(): string
    {
        return Node\Param::class;
    }

    /**
     * @param Node\Param $node
     * @return iterable<class-string>
     */
    protected function extractNodeClassNames(Node $node, Scope $scope): iterable
    {
        return namesToClassStrings(TypeNodeParser::parse($node->type, $scope));
    }
}
