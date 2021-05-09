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
     * @var int
     */
    private static $totalErrors = 0;
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
        self::$totalErrors += 1;
    }

    /**
     * @param string $message
     */
    public static function addFatalError(string $message): void
    {
        self::$fatalErrors[] = $message;
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
     * @return int
     */
    public static function getTotalErrors(): int
    {
        return self::$totalErrors;
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
