<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\ShouldInclude;

use PHPat\Rule\Extractor\Relation\AllTraitsExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
final class IncludedTraitsRule extends ShouldInclude implements Rule
{
    use AllTraitsExtractor;
}
