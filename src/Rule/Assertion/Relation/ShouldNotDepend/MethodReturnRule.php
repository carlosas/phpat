<?php

declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\ShouldNotDepend;

use PHPat\Rule\Extractor\Relation\MethodReturnExtractor;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Stmt\ClassMethod>
 */
class MethodReturnRule extends ShouldNotDepend implements Rule
{
    use MethodReturnExtractor;
}
