<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion\Relation\ApplyAttribute;

use PHPat\Rule\Extractor\Relation\ClassAttributeExtractor;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<InClassNode>
 */
final class ClassAttributeRule extends ApplyAttributeAssertion implements Rule
{
    use ClassAttributeExtractor;
}
