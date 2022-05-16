<?php

namespace PhpAT\Rule\Assertion\Dependency\MustNotDepend;

use PhpAT\Rule\Assertion\Traits\ClassConstantNode;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Expr\ClassConstFetch>
 */
class ClassConstantRule extends MustNotDepend implements Rule
{
    use ClassConstantNode;
}
