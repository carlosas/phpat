<?php

declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\ShouldNotDepend;

use PHPat\Rule\Extractor\Relation\DocComment\MethodScope\ParamTagExtractor;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Stmt\ClassMethod>
 */
class DocParamTagRule extends ShouldNotDepend implements Rule
{
    use ParamTagExtractor;
}
