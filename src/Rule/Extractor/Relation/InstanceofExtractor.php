<?php declare(strict_types=1);

namespace PHPat\Rule\Extractor\Relation;

use PHPat\Parser\TypeNodeParser;
use PhpParser\Node;
use PHPStan\Analyser\Scope;

trait InstanceofExtractor
{
    public function getNodeType(): string
    {
        return Node\Expr\Instanceof_::class;
    }

    /**
     * @param  Node\Expr\Instanceof_ $node
     * @return array<class-string>
     */
    protected function extractNodeClassNames(Node $node, Scope $scope): array
    {
        return namesToClassStrings(TypeNodeParser::parse($node->class, $scope));
    }
}
