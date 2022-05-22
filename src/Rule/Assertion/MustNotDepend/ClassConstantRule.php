<?php

namespace PHPat\Rule\Assertion\MustNotDepend;

use PHPat\Rule\Traits\ClassConstantNode;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Expr\ClassConstFetch>
 */
class ClassConstantRule extends MustNotDepend implements Rule
{
    use ClassConstantNode;
}
