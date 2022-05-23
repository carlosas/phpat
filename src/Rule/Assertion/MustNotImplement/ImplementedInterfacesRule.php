<?php

namespace PHPat\Rule\Assertion\MustNotImplement;

use PHPat\Rule\Traits\Interfaces;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
class ImplementedInterfacesRule extends MustNotImplement implements Rule
{
    use Interfaces;
}
