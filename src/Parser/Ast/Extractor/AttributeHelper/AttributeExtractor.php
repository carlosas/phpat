<?php

namespace PhpAT\Parser\Ast\Extractor\AttributeHelper;

use PhpParser\Node\AttributeGroup;

class AttributeExtractorFactory
{
    public function create(): AttributeExtractorInterface
    {
        if (class_exists(AttributeGroup::class)) {
            return new
        }
    }
}