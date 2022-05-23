<?php

namespace PHPat\Rule\Assertion\ShouldNotDepend;

use PHPat\Rule\Traits\MethodReturnNode;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Stmt\ClassMethod>
 */
class MethodReturnRule extends ShouldNotDepend implements Rule
{
    use MethodReturnNode;
}
