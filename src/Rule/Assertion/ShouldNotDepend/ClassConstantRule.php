<?php

namespace PHPat\Rule\Assertion\ShouldNotDepend;

use PHPat\Rule\Traits\ClassConstantNode;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Expr\ClassConstFetch>
 */
class ClassConstantRule extends ShouldNotDepend implements Rule
{
    use ClassConstantNode;
}
