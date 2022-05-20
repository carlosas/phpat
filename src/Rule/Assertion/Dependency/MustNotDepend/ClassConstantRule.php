<?php

namespace PHPat\Rule\Assertion\Dependency\MustNotDepend;

use PHPat\Rule\Assertion\Traits\ClassConstantNode;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Expr\ClassConstFetch>
 */
class ClassConstantRule extends MustNotDepend implements Rule
{
    use ClassConstantNode;
}
