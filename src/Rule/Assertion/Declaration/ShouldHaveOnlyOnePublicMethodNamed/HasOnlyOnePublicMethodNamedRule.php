<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration\ShouldHaveOnlyOnePublicMethodNamed;

use PHPat\Rule\Extractor\Declaration\PublicMethodNamedExtractor;

final class HasOnlyOnePublicMethodNamedRule extends ShouldHaveOnlyOnePublicMethodNamed
{
    use PublicMethodNamedExtractor;
}
