<?php declare(strict_types=1);

namespace PHPat\Rule\Extractor\Relation;

use PhpParser\Node;
use PHPStan\Analyser\Scope;

trait AllAttributesExtractor
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

        if ($node instanceof Node\AttributeGroup) {
            return $this->getFromAttributeGroup($node);
        }

        if ($node instanceof Node\Expr\ArrowFunction || $node instanceof Node\Expr\Closure) {
            foreach ($node->attrGroups as $group) {
                $names = array_merge($names, $this->getFromAttributeGroup($group));
            }
        }

        return $names;
    }

    /**
     * @return list<class-string>
     */
    private function getFromAttributeGroup(Node\AttributeGroup $group): array
    {
        $names = [];
        foreach ($group->attrs as $attr) {
            /** @var class-string $name */
            $name = $attr->name->toString();
            $names[] = $name;
        }

        return $names;
    }
}
