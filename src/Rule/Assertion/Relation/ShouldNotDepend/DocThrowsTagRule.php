<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\ShouldNotDepend;

use PHPat\Rule\Extractor\Relation\DocComment\MethodScope\ThrowsTagExtractor;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Stmt\ClassMethod>
 */
final class DocThrowsTagRule extends ShouldNotDepend implements Rule
{
    use ThrowsTagExtractor;
}
