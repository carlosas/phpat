<?php declare(strict_types=1);

namespace PHPat\Rule\Extractor\Relation;

use PHPat\Parser\TypeNodeParser;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;

trait MethodReturnExtractor
{
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * @param  ClassMethod         $node
     * @return array<class-string>
     */
    protected function extractNodeClassNames(Node $node, Scope $scope): array
    {
        return namesToClassStrings(TypeNodeParser::parse($node->getReturnType(), $scope));
    }
}
