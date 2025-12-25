<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\CanOnlyDepend;

use PHPat\Rule\Extractor\Relation\AttributeParamExtractor;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Attribute>
 */
final class AttributeParamRule extends CanOnlyDepend implements Rule
{
    use AttributeParamExtractor;
}
