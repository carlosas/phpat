<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration\ShouldBeReadonly;

use PHPat\Rule\Extractor\Declaration\ReadonlyExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
final class IsReadonlyRule extends ShouldBeReadonly implements Rule
{
    use ReadonlyExtractor;
}
