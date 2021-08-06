<?php

namespace PhpAT\Parser\Ast\Extractor\AttributeHelper;

use PHPStan\BetterReflection\Reflection\ReflectionClass;
use PHPStan\BetterReflection\Reflection\ReflectionMethod;

interface AttributeExtractorInterface
{
    public function getFromReflectionClass(ReflectionClass $class): array;

    public function getFromReflectionMethod(ReflectionMethod $class): array;
}
