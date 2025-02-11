<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration\ShouldHaveOnlyPublicMethodNamed;

use PHPat\Rule\Extractor\Declaration\PublicMethodNamedExtractor;

final class HasOnlyPublicMethodNamedRule extends ShouldHaveOnlyPublicMethodNamed
{
    use PublicMethodNamedExtractor;
}
