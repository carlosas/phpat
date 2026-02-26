<?php declare(strict_types=1);

namespace PHPat\Rule\Assertion;

enum Constraint: string
{
    case Should = 'should';
    case ShouldNot = 'shouldNot';
    case CanOnly = 'canOnly';
}
