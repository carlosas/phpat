<?php

declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration\ShouldBeImmutable;

use PHPat\Rule\Extractor\Declaration\MutablePropertyExtractor;
use PHPStan\Node\ClassPropertyNode;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<ClassPropertyNode>
 */
class MutablePropertyRule extends ShouldBeImmutable implements Rule
{
    use MutablePropertyExtractor;
}
