<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\ShouldNotDepend;

use PHPat\Rule\Extractor\Relation\DocComment\MethodScope\ReturnTagExtractor;
use PHPStan\Node\InClassMethodNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassMethodNode>
 */
final class DocReturnTagRule extends ShouldNotDepend implements Rule
{
    use ReturnTagExtractor;
}
