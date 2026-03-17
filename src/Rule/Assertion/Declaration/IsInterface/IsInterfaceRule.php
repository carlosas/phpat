<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration\IsInterface;

use PHPat\Rule\Extractor\Declaration\InterfaceExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
final class IsInterfaceRule extends InterfaceDeclaration implements Rule
{
    use InterfaceExtractor;
}
