<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration\ShouldBeNamed;

use PHPat\Rule\Assertion\Declaration\ShouldBeAbstract\ShouldBeAbstract;
use PHPat\Rule\Extractor\Declaration\AbstractExtractor;
use PHPat\Rule\Extractor\Declaration\ClassnameExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
final class NameRule extends ShouldBeAbstract implements Rule
{
    use ClassnameExtractor;
}
