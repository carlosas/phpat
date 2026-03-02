<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\Depend;

use PHPat\Rule\Extractor\Relation\DocComment\MethodScope\VarTagExtractor;
use PhpParser\Node;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Node\Expr\Variable>
 */
final class DocVarTagRule extends DependAssertion implements Rule
{
    use VarTagExtractor;
}
