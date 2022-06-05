<?php

declare(strict_types=1);

namespace PHPat\Rule\Assertion\ShouldNotImplement;

use PHPat\Rule\Extractor\AllInterfacesExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
class ImplementedInterfacesRule extends ShouldNotImplement implements Rule
{
    use AllInterfacesExtractor;
}
