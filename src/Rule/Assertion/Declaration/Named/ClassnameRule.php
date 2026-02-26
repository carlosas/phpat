<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration\Named;

use PHPat\Rule\Extractor\Declaration\ClassnameExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
final class ClassnameRule extends NamedDeclaration implements Rule
{
    use ClassnameExtractor;
}
