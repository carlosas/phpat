<?php

namespace PhpAT\Parser\Ast\Extractor\AttributeHelper;

use phpDocumentor\Reflection\Types\Context;
use PhpParser\Node\AttributeGroup;

class AttributeExtractorFactory
{
    public function create(Context $context): AttributeExtractorInterface
    {
        if (class_exists(AttributeGroup::class)) {
            return new AttributeExtractor($context);
        }

        return new NullAttributeExtractor();
    }
}
