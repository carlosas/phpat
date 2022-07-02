<?php

declare(strict_types=1);

namespace PHPat\Rule\Assertion\ShouldExtend;

use PHPat\Rule\Extractor\AllParentsExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
class ParentClassRule extends ShouldExtend implements Rule
{
    use AllParentsExtractor;
}
