<?php

declare(strict_types=1);

namespace PHPat\Rule\Assertion\Declaration\ShouldBeImmutable;

use PHPat\Rule\Extractor\Declaration\PropertyAssignedOutOfConstructorExtractor;
use PhpParser\Node\Expr\Assign;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Assign>
 */
class PropertyAssignationRule extends ShouldBeImmutable implements Rule
{
    use PropertyAssignedOutOfConstructorExtractor;
}
