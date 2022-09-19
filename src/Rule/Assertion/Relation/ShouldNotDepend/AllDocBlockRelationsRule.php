<?php

declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\ShouldNotDepend;

use PHPat\Rule\Extractor\Relation\DocComment\AllDocBlockRelations;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node>
 */
class AllDocBlockRelationsRule extends ShouldNotDepend implements Rule
{
    use AllDocBlockRelations;
}
