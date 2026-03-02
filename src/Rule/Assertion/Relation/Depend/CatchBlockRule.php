<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\Depend;

use PHPat\Rule\Extractor\Relation\CatchBlockExtractor;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Stmt\Catch_>
 */
final class CatchBlockRule extends DependAssertion implements Rule
{
    use CatchBlockExtractor;
}
