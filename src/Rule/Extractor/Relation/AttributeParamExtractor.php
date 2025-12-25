<?php declare(strict_types=1);

namespace PHPat\Rule\Extractor\Relation;

use PHPat\Parser\TypeNodeParser;
use PhpParser\Node;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;

trait AttributeParamExtractor
{
    public function getNodeType(): string
    {
        return Node::class;
    }

    /**
     * @return list<class-string>
     */
    protected function extractNodeClassNames(Node $node, Scope $scope): array
    {
        if (!$scope->isInClass()) {
            return [];
        }

        $names = [];

        if ($node instanceof Node\Attribute) {
            $names = array_merge($names, $this->extractFromAttribute($node, $scope));
        } elseif ($node instanceof Node\Expr\ArrowFunction || $node instanceof Node\Expr\Closure) {
            foreach ($node->attrGroups as $group) {
                foreach ($group->attrs as $attr) {
                    $names = array_merge($names, $this->extractFromAttribute($attr, $scope));
                }
            }
        }

        return $names;
    }

    /**
     * @return list<class-string>
     */
    private function extractFromAttribute(Node\Attribute $attribute, Scope $scope): array
    {
        $names = [];
        $nodeFinder = new NodeFinder();

        /** @var list<Node\Expr\ClassConstFetch> $constFetches */
        $constFetches = $nodeFinder->findInstanceOf($attribute->args, Node\Expr\ClassConstFetch::class);
        foreach ($constFetches as $constFetch) {
            if ($constFetch->class instanceof Node\Name) {
                array_push($names, ...namesToClassStrings(TypeNodeParser::parse($constFetch->class, $scope)));
            }
        }

        /** @var list<Node\Expr\New_> $newExpressions */
        $newExpressions = $nodeFinder->findInstanceOf($attribute->args, Node\Expr\New_::class);
        foreach ($newExpressions as $newExpression) {
            if ($newExpression->class instanceof Node\Name) {
                array_push($names, ...namesToClassStrings(TypeNodeParser::parse($newExpression->class, $scope)));
            }
        }

        return array_values($names);
    }
}
