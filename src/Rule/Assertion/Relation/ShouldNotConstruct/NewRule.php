<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\ShouldNotConstruct;

use PHPat\Rule\Extractor\Relation\NewExtractor;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Expr\New_>
 */
final class NewRule extends ShouldNotConstruct implements Rule
{
    use NewExtractor;
}
