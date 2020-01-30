<?php

declare(strict_types=1);

namespace PhpAT\App;

class RuleValidationStorage
{
    /**
     * @var array
     */
    private static $warnings = [];
    /**
     * @var array
     */
    private static $errors = [];
    /**
     * @var array
     */
    private static $fatalErrors = [];
    /**
     * @var bool
     */
    private static $anyRuleHadErrors = false;
    /**
     * @var bool
     */
    private static $lastRuleHadErrors = false;

    /**
     * @var float
     */
    private static $startTime = 0;

    /**
     * @param float $time
     */
    public static function setStartTime(float $time): void
    {
        self::$startTime = $time;
    }

    /**
     * @param string $message
     */
    public static function addWarning(string $message): void
    {
        self::$warnings[] = $message;
    }

    /**
     * @param string $message
     */
    public static function addError(string $message): void
    {
        self::$errors[] = $message;
        self::$lastRuleHadErrors = true;
        self::$anyRuleHadErrors = true;
    }

    /**
     * @param string $message
     */
    public static function addFatalError(string $message): void
    {
        self::$fatalErrors[] = $message;
        self::$anyRuleHadErrors = true;
    }

    /**
     * @return array
     */
    public static function flushWarnings(): array
    {
        $w = self::$warnings;
        self::$warnings = [];

        return $w;
    }

    /**
     * @return array
     */
    public static function flushErrors(): array
    {
        $e = self::$errors;
        self::$errors = [];
        self::$lastRuleHadErrors = false;

        return $e;
    }

    /**
     * @return bool
     */
    public static function anyRuleHadErrors(): bool
    {
        return self::$anyRuleHadErrors;
    }

    /**
     * @return bool
     */
    public static function lastRuleHadErrors(): bool
    {
        return self::$lastRuleHadErrors;
    }

    /**
     * @return float
     */
    public static function getStartTime(): float
    {
        return (float) self::$startTime;
    }
}
