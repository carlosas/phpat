<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration\OnePublicMethod;

use PHPat\Rule\Extractor\Declaration\OnePublicMethodExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
final class HasOnlyOnePublicMethodRule extends OnePublicMethodDeclaration implements Rule
{
    use OnePublicMethodExtractor;
}
