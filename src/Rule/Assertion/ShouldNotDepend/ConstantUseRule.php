<?php

namespace PHPat\Rule\Assertion\ShouldNotDepend;

use PHPat\Rule\Extractor\ConstantUseExtractor;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Expr\ClassConstFetch>
 */
class ConstantUseRule extends ShouldNotDepend implements Rule
{
    use ConstantUseExtractor;
}
