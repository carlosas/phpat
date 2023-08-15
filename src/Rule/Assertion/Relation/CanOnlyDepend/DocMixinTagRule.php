<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\CanOnlyDepend;

use PHPat\Rule\Extractor\Relation\DocComment\ClassScope\MixinTagExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
final class DocMixinTagRule extends CanOnlyDepend implements Rule
{
    use MixinTagExtractor;
}
