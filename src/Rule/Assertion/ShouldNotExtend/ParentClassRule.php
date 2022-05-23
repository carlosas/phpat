<?php

namespace PHPat\Rule\Assertion\ShouldNotExtend;

use PHPat\Rule\Traits\ParentClass;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
class ParentClassRule extends ShouldNotExtend implements Rule
{
    use ParentClass;
}
