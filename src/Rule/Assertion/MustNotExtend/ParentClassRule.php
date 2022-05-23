<?php

namespace PHPat\Rule\Assertion\MustNotExtend;

use PHPat\Rule\Traits\ParentClass;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
class ParentClassRule extends MustNotExtend implements Rule
{
    use ParentClass;
}
