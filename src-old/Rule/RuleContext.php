<?php

namespace PHPatOld\Rule;

class RuleContext
{
    private static string $ruleName = '';

    public static function startRule(string $ruleName)
    {
        self::$ruleName = $ruleName;
    }

    public static function ruleName(): string
    {
        return self::$ruleName;
    }
}
