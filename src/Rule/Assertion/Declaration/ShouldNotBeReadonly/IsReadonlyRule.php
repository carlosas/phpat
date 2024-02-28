<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration\ShouldNotBeReadonly;

use PHPat\Rule\Extractor\Declaration\ReadonlyExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
final class IsReadonlyRule extends ShouldNotBeReadonly implements Rule
{
    use ReadonlyExtractor;
}
