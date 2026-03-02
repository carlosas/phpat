<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration\IsFinal;

use PHPat\Rule\Extractor\Declaration\FinalExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
final class IsFinalRule extends FinalDeclaration implements Rule
{
    use FinalExtractor;
}
