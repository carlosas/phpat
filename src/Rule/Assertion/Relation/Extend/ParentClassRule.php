<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\Extend;

use PHPat\Rule\Extractor\Relation\AllParentsExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
final class ParentClassRule extends ExtendAssertion implements Rule
{
    use AllParentsExtractor;
}
