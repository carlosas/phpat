<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration\ShouldBeFinal;

use PHPat\Rule\Extractor\Declaration\FinalExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
final class IsFinalRule extends ShouldBeFinal implements Rule
{
    use FinalExtractor;
}
