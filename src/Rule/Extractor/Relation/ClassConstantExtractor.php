<?php declare(strict_types=1);

namespace PHPat\Rule\Extractor\Relation;

use PHPat\Parser\TypeNodeParser;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassConst;
use PHPStan\Analyser\Scope;

trait ClassConstantExtractor
{
    public function getNodeType(): string
    {
        return ClassConst::class;
    }

    /**
     * @param  ClassConst          $node
     * @return array<class-string>
     */
    protected function extractNodeClassNames(Node $node, Scope $scope): array
    {
        return namesToClassStrings(TypeNodeParser::parse($node->type, $scope));
    }
}
