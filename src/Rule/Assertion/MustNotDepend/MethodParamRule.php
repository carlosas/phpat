<?php

namespace PHPat\Rule\Assertion\MustNotDepend;

use PHPat\Rule\Traits\MethodParamNode;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Param>
 */
class MethodParamRule extends MustNotDepend implements Rule
{
    use MethodParamNode;
}
