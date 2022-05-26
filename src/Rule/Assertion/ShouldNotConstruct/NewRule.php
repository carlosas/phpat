<?php

namespace PHPat\Rule\Assertion\ShouldNotConstruct;

use PHPat\Rule\Extractor\NewExtractor;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Expr\New_>
 */
class NewRule extends ShouldNotConstruct implements Rule
{
    use NewExtractor;
}
