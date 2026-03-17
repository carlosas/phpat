<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\IncludeTrait;

use PHPat\Rule\Extractor\Relation\AllTraitsExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
final class IncludedTraitsRule extends IncludeAssertion implements Rule
{
    use AllTraitsExtractor;
}
