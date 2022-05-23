<?php

namespace PHPat\Rule\Assertion\ShouldNotDepend;

use PHPat\Rule\Traits\NewNode;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Expr\New_>
 */
class NewRule extends ShouldNotDepend implements Rule
{
    use NewNode;
}
