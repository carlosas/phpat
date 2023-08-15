<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\CanOnlyDepend;

use PHPat\Rule\Extractor\Relation\MethodParamExtractor;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Param>
 */
final class MethodParamRule extends CanOnlyDepend implements Rule
{
    use MethodParamExtractor;
}
