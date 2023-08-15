<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\CanOnlyDepend;

use PHPat\Rule\Extractor\Relation\DocComment\MethodScope\ReturnTagExtractor;
use PHPStan\Node\InClassMethodNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassMethodNode>
 */
final class DocReturnTagRule extends CanOnlyDepend implements Rule
{
    use ReturnTagExtractor;
}
