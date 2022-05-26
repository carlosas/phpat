<?php

namespace PHPat\Rule\Assertion\ShouldNotDepend;

use PHPat\Rule\Extractor\MethodParamExtractor;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Param>
 */
class MethodParamRule extends ShouldNotDepend implements Rule
{
    use MethodParamExtractor;
}
