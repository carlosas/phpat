<?php

namespace PhpAT\Rule\Assertion\Dependency\MustNotDepend;

use PhpAT\Rule\Assertion\Traits\NewNode;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Expr\New_>
 */
class NewRule extends MustNotDepend implements Rule
{
    use NewNode;
}
