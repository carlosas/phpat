<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration\OnePublicMethodNamed;

use PHPat\Rule\Extractor\Declaration\PublicMethodNamedExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
final class HasOnlyOnePublicMethodNamedRule extends OnePublicMethodNamedDeclaration implements Rule
{
    use PublicMethodNamedExtractor;
}
