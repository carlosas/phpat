<?php

namespace PhpAT\Parser\Ast\Extractor\AttributeHelper;

use phpDocumentor\Reflection\Types\Context;
use PHPStan\BetterReflection\Reflection\ReflectionAttribute;
use PHPStan\BetterReflection\Reflection\ReflectionClass;
use PHPStan\BetterReflection\Reflection\ReflectionMethod;

class AttributeExtractor implements AttributeExtractorInterface
{
    /** @var Context */
    private $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function getFromReflectionClass(ReflectionClass $class): array
    {
        foreach ($class->getAttributes() as $attribute) {
            $result[] = $attribute->getName();
            //TODO: attribute arguments might use types
        }

        return $result ?? [];
    }

    public function getFromReflectionMethod(ReflectionMethod $method): array
    {
        $ast = $method->getAst();
        foreach ($ast->attrGroups ?? [] as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                $result[] = (new ReflectionAttribute(
                    $attr->name->toString(),
                    [] //TODO: attribute arguments might use types
                ))->getName();
            }
        }

        return $result ?? [];
    }
}
