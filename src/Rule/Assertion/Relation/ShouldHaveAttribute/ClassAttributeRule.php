<?php

declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\ShouldHaveAttribute;

use PHPat\Rule\Extractor\Relation\ClassAttributeExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
class ClassAttributeRule extends ShouldHaveAttribute implements Rule
{
    use ClassAttributeExtractor;
}
