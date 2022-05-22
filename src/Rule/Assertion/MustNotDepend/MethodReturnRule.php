<?php

namespace PHPat\Rule\Assertion\MustNotDepend;

use PHPat\Rule\Traits\MethodReturnNode;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Stmt\ClassMethod>
 */
class MethodReturnRule extends MustNotDepend implements Rule
{
    use MethodReturnNode;
}
