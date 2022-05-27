<?php

namespace PHPat\Rule\Assertion\ShouldImplement;

use PHPat\Rule\Extractor\AllInterfacesExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
class ImplementedInterfacesRule extends ShouldImplement implements Rule
{
    use AllInterfacesExtractor;
}
