<?php

namespace PHPat\Rule\Assertion\Dependency\MustNotDepend;

use PHPat\Rule\Assertion\Traits\NewNode;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Expr\New_>
 */
class NewRule extends MustNotDepend implements Rule
{
    use NewNode;
}
