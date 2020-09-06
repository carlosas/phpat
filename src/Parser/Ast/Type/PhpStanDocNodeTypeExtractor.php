<?php

namespace PhpAT\Parser\Ast\Type;

use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\ExtendsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ImplementsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueParameterNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MixinTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PropertyTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ThrowsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\UsesTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;

class PhpStanDocNodeTypeExtractor
{
    /**
     * @param Node $node
     * @return TypeNode[]
     */
    public function getTypesNodes(Node $node): array
    {
        if ($node instanceof PhpDocTagNode) {
            return $this->getTypesFromTagNode($node);
        }

        if ($node instanceof PhpDocTagValueNode) {
            return $this->getTypesFromTagValueNode($node);
        }

        return $this->getTypesFromOthersNode($node);
    }

    /**
     * @param PhpDocTagNode $node
     * @return TypeNode[]
     */
    private function getTypesFromTagNode(PhpDocTagNode $node): array
    {
        return $this->getTypesNodes($node->value);
    }

    /**
     * @param PhpDocTagValueNode $node
     * @return TypeNode[]
     */
    private function getTypesFromTagValueNode(PhpDocTagValueNode $node): array
    {
        switch (true) {
            case $node instanceof MethodTagValueNode:
                $types[] = $node->returnType;
                foreach ($node->parameters as $p) {
                    $types = array_merge($types, $this->getTypesNodes($p));
                }
                break;

            case $node instanceof TemplateTagValueNode:
                $types[] = $node->bound ?? null;
                break;

            case $node instanceof ParamTagValueNode:
            case $node instanceof ExtendsTagValueNode:
            case $node instanceof PropertyTagValueNode:
            case $node instanceof VarTagValueNode:
            case $node instanceof UsesTagValueNode:
            case $node instanceof ReturnTagValueNode:
            case $node instanceof ImplementsTagValueNode:
            case $node instanceof ThrowsTagValueNode:
            case $node instanceof MixinTagValueNode:
                $types[] = $node->type ?? null;
                break;

            default:
                $types = [];
        }

        return $types;
    }

    /**
     * @param Node $node
     * @return TypeNode[]
     */
    public function getTypesFromOthersNode(Node $node): array
    {
        switch (true) {
            case $node instanceof TypeNode:
                $types[] = $node;
                break;

            case $node instanceof PhpDocNode:
                foreach ($node->children as $c) {
                    $types = $this->getTypesNodes($c);
                }
                break;

            case $node instanceof MethodTagValueParameterNode:
                if ($node->type !== null) {
                    $types[] = $node->type;
                }
                break;
        }

        return $types ?? [];
    }
}
