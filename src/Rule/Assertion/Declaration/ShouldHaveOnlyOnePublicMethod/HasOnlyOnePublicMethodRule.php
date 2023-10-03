<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration\ShouldHaveOnlyOnePublicMethod;

use PHPat\Rule\Extractor\Declaration\OnePublicMethodExtractor;

final class HasOnlyOnePublicMethodRule extends ShouldHaveOnlyOnePublicMethod
{
    use OnePublicMethodExtractor;
}
