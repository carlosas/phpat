<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\Depend;

use PHPat\Rule\Extractor\Relation\DocComment\MethodScope\ParamTagExtractor;
use PHPStan\Node\InClassMethodNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassMethodNode>
 */
final class DocParamTagRule extends DependAssertion implements Rule
{
    use ParamTagExtractor;
}
