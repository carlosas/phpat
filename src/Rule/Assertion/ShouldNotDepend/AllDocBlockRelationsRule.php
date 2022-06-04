<?php

namespace PHPat\Rule\Assertion\ShouldNotDepend;

use PHPat\Rule\Extractor\AllDocBlockRelations;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node>
 */
class AllDocBlockRelationsRule extends ShouldNotDepend implements Rule
{
    use AllDocBlockRelations;
}
