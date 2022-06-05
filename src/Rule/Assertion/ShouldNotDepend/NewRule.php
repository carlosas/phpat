<?php

declare(strict_types=1);

namespace PHPat\Rule\Assertion\ShouldNotDepend;

use PHPat\Rule\Extractor\NewExtractor;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Expr\New_>
 */
class NewRule extends ShouldNotDepend implements Rule
{
    use NewExtractor;
}
