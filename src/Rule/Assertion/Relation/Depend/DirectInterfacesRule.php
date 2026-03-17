<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\Depend;

use PHPat\Rule\Extractor\Relation\DirectInterfacesExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
final class DirectInterfacesRule extends DependAssertion implements Rule
{
    use DirectInterfacesExtractor;
}
