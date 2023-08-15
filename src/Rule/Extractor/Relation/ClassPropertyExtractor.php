<?php declare(strict_types=1);

namespace PHPat\Rule\Extractor\Relation;

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
     * @param  Node\Stmt\Property  $node
     * @return array<class-string>
     */
    protected function extractNodeClassNames(Node $node, Scope $scope): array
    {
        return namesToClassStrings(TypeNodeParser::parse($node->type, $scope));
    }
}
