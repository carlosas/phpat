<?php

namespace PHPat\Rule\Assertion\ShouldNotImplement;

use PHPat\Rule\Traits\Interfaces;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
class ImplementedInterfacesRule extends ShouldNotImplement implements Rule
{
    use Interfaces;
}
