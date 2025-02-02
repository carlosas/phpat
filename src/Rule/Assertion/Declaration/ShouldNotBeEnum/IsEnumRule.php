<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration\ShouldNotBeEnum;

use PHPat\Rule\Extractor\Declaration\EnumExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
final class IsEnumRule extends ShouldNotBeEnum implements Rule
{
    use EnumExtractor;
}
