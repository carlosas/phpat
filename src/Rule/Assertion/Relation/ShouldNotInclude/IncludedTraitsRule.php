<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\ShouldNotInclude;

use PHPat\Rule\Extractor\Relation\AllTraitsExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
final class IncludedTraitsRule extends ShouldNotInclude implements Rule
{
    use AllTraitsExtractor;
}
