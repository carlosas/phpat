<?php

namespace PHPat\Rule\Extractor;

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
     * @param Node\Expr\ClassConstFetch $node
     * @return iterable<class-string>
     */
    protected function extractNodeClassNames(Node $node, Scope $scope): iterable
    {
        $classes         = TypeNodeParser::parse($node->class, $scope);
        $class           = reset($classes);
        $reflectedClass  = $this->reflectionProvider->getClass($class->toString());
        $reflectedMethod = $reflectedClass->getMethod($node->name, $scope);
        //TODO: check if the method is a named constructor

        return namesToClassStrings(TypeNodeParser::parse($node->class, $scope));
    }
}
