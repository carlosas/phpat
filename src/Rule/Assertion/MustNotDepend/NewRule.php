<?php

namespace PHPat\Rule\Assertion\MustNotDepend;

use PHPat\Rule\Traits\NewNode;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Expr\New_>
 */
class NewRule extends MustNotDepend implements Rule
{
    use NewNode;
}
