<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration\ShouldBeInvokable;

use PHPat\Rule\Extractor\Declaration\InvokableExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
final class IsInvokableRule extends ShouldBeInvokable implements Rule
{
    use InvokableExtractor;
}
