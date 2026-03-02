<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\Depend;

use PHPat\Rule\Extractor\Relation\InstanceofExtractor;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Expr\Instanceof_>
 */
final class InstanceofRule extends DependAssertion implements Rule
{
    use InstanceofExtractor;
}
