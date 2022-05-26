<?php

namespace PHPat\Rule\Assertion\ShouldNotExtend;

use PHPat\Rule\Extractor\AllParentsExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
class ParentClassRule extends ShouldNotExtend implements Rule
{
    use AllParentsExtractor;
}
