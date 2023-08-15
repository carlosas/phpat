<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\ShouldNotImplement;

use PHPat\Rule\Extractor\Relation\AllInterfacesExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
final class ImplementedInterfacesRule extends ShouldNotImplement implements Rule
{
    use AllInterfacesExtractor;
}
