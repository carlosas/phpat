<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration\IsEnum;

use PHPat\Rule\Extractor\Declaration\EnumExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
final class IsEnumRule extends EnumDeclaration implements Rule
{
    use EnumExtractor;
}
