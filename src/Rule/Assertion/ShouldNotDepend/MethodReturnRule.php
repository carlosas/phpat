<?php

declare(strict_types=1);

namespace PHPat\Rule\Assertion\ShouldNotDepend;

use PHPat\Rule\Extractor\MethodReturnExtractor;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Stmt\ClassMethod>
 */
class MethodReturnRule extends ShouldNotDepend implements Rule
{
    use MethodReturnExtractor;
}
