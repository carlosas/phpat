<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\CanOnlyDepend;

use PHPat\Rule\Extractor\Relation\ConstantUseExtractor;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Expr\ClassConstFetch>
 */
final class ConstantUseRule extends CanOnlyDepend implements Rule
{
    use ConstantUseExtractor;
}
