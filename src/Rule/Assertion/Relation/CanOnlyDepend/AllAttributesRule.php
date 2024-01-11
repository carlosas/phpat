<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\CanOnlyDepend;

use PHPat\Rule\Extractor\Relation\AllAttributesExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
final class AllAttributesRule extends CanOnlyDepend implements Rule
{
    use AllAttributesExtractor;
}
