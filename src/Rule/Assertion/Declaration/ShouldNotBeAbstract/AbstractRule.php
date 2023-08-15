<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration\ShouldNotBeAbstract;

use PHPat\Rule\Extractor\Declaration\AbstractExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
final class AbstractRule extends ShouldNotBeAbstract implements Rule
{
    use AbstractExtractor;
}
