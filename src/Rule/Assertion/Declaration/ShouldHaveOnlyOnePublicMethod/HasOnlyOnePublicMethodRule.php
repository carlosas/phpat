<?php

namespace PHPat\Rule\Assertion\Declaration\ShouldHaveOnlyOnePublicMethod;

use PHPat\Rule\Extractor\Declaration\OnePublicMethodExtractor;

class HasOnlyOnePublicMethodRule extends ShouldHaveOnlyOnePublicMethod
{
    use OnePublicMethodExtractor;
}
