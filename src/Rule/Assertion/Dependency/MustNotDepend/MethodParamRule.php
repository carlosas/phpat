<?php

namespace PHPat\Rule\Assertion\Dependency\MustNotDepend;

use PHPat\Rule\Assertion\Traits\MethodParamNode;
use PHPStan\Node\ClassPropertiesNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<ClassPropertiesNode>
 */
class MethodParamRule extends MustNotDepend implements Rule
{
    use MethodParamNode;
}
