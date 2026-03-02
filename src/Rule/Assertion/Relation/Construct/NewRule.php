<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\Construct;

use PHPat\Rule\Extractor\Relation\NewExtractor;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Expr\New_>
 */
final class NewRule extends ConstructAssertion implements Rule
{
    use NewExtractor;
}
