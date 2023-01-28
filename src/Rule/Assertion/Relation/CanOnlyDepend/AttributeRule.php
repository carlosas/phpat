<?php

declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\CanOnlyDepend;

use PHPat\Rule\Extractor\Relation\AttributeExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
class AttributeRule extends CanOnlyDepend implements Rule
{
    use AttributeExtractor;
}
