<?php declare(strict_types=1);

namespace PHPat\Rule\Extractor\Relation;

use PhpParser\Node;
use PhpParser\Node\Stmt\Catch_;
use PHPStan\Analyser\Scope;

trait CatchBlockExtractor
{
    public function getNodeType(): string
    {
        return Catch_::class;
    }

    /**
     * @param  Catch_              $node
     * @return array<class-string>
     */
    protected function extractNodeClassNames(Node $node, Scope $scope): array
    {
        return namesToClassStrings($node->types);
    }
}
