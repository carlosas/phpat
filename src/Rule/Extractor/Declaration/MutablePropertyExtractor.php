<?php

declare(strict_types=1);

namespace PHPat\Rule\Extractor\Declaration;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\ClassPropertyNode;

trait MutablePropertyExtractor
{
    public function getNodeType(): string
    {
        return ClassPropertyNode::class;
    }

    /**
     * @param ClassPropertyNode $node
     */
    protected function meetsDeclaration(Node $node, Scope $scope): bool
    {
        $class = $scope->getClassReflection();
        if ($class && $class->isReadOnly()) {
            return true;
        }

        if (!$node->isPublic() || $node->isReadonly()) {
            return true;
        }

        return false;
    }
}
