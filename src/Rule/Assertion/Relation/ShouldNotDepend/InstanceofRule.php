<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\ShouldNotDepend;

use PHPat\Rule\Extractor\Relation\InstanceofExtractor;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Expr\Instanceof_>
 */
final class InstanceofRule extends ShouldNotDepend implements Rule
{
    use InstanceofExtractor;
}
