<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration\ShouldNotExist;

use PHPat\Rule\Extractor\Declaration\ExistsExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
final class ExistsRule extends ShouldNotExist implements Rule
{
    use ExistsExtractor;
}
