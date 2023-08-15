<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\ShouldNotDepend;

use PHPat\Rule\Extractor\Relation\StaticMethodCallExtractor;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Expr\StaticCall>
 */
final class StaticMethodRule extends ShouldNotDepend implements Rule
{
    use StaticMethodCallExtractor;
}
