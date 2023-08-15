<?php declare(strict_types=1);

namespace PHPat\Rule\Extractor\Relation;

use PHPat\Parser\TypeNodeParser;
use PhpParser\Node;
use PHPStan\Analyser\Scope;

trait NamedConstructorExtractor
{
    public function getNodeType(): string
    {
        return Node\Expr\ClassConstFetch::class;
    }

    /**
     * @param  Node\Expr\ClassConstFetch $node
     * @return array<class-string>
     */
    protected function extractNodeClassNames(Node $node, Scope $scope): array
    {
        $classes = TypeNodeParser::parse($node->class, $scope);
        $class = reset($classes);
        $reflectedClass = $this->reflectionProvider->getClass($class->toString());
        $reflectedMethod = $reflectedClass->getMethod($node->name, $scope);
        // TODO: check if the method is a named constructor

        return namesToClassStrings(TypeNodeParser::parse($node->class, $scope));
    }
}
