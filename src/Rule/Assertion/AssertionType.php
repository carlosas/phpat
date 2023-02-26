<?php

declare(strict_types=1);

namespace PHPat\Rule\Assertion;

class AssertionType
{
    public const SHOULD = 'should';
    public const SHOULD_NOT = 'should not';
    public const CAN_ONLY = 'can only';

    public static function isValid(string $type): bool
    {
        return in_array($type, [self::SHOULD, self::SHOULD_NOT, self::CAN_ONLY]);
    }
}
