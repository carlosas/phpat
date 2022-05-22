<?php

namespace PHPat\Rule\Assertion\MustNotConstruct;

use PHPat\Rule\Traits\NewNode;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Expr\New_>
 */
class NewRule extends MustNotConstruct implements Rule
{
    use NewNode;
}
