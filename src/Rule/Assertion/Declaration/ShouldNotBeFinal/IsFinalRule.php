<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration\ShouldNotBeFinal;

use PHPat\Rule\Extractor\Declaration\FinalExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
final class IsFinalRule extends ShouldNotBeFinal implements Rule
{
    use FinalExtractor;
}
