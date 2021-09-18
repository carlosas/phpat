<?php

namespace PhpAT\Parser\Ast\Extractor\AttributeHelper;

use PhpAT\Parser\Ast\ClassContext;
use PhpParser\Node\AttributeGroup;

class AttributeExtractorFactory
{
    public function create(ClassContext $context): AttributeExtractorInterface
    {
        if (class_exists(AttributeGroup::class)) {
            return new AttributeExtractor($context);
        }

        return new NullAttributeExtractor();
    }
}
