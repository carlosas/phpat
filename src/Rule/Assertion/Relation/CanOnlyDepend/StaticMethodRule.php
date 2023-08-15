<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\CanOnlyDepend;

use PHPat\Rule\Extractor\Relation\StaticMethodCallExtractor;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Expr\StaticCall>
 */
final class StaticMethodRule extends CanOnlyDepend implements Rule
{
    use StaticMethodCallExtractor;
}
