<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration\ShouldBeNamed;

use PHPat\Rule\Extractor\Declaration\ClassnameExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
final class ClassnameRule extends ShouldBeNamed implements Rule
{
    use ClassnameExtractor;
}
