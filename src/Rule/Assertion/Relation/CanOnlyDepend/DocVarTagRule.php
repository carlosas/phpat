<?php

declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\CanOnlyDepend;

use PHPat\Rule\Extractor\Relation\DocComment\MethodScope\VarTagExtractor;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Expr\Variable>
 */
class DocVarTagRule extends CanOnlyDepend implements Rule
{
    use VarTagExtractor;
}
