<?php

namespace PhpAT\Parser\Ast\Extractor\AttributeHelper;

use PHPStan\BetterReflection\Reflection\ReflectionClass;
use PHPStan\BetterReflection\Reflection\ReflectionMethod;

class NullAttributeExtractor implements AttributeExtractorInterface
{
    public function getFromReflectionClass(ReflectionClass $class): array
    {
        return [];
    }

    public function getFromReflectionMethod(ReflectionMethod $class): array
    {
        return [];
    }
}
