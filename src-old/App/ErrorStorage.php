<?php

declare(strict_types=1);

namespace PHPatOld\App;

class ErrorStorage
{
    private static array $warnings         = [];
    private static array $errors           = [];
    private static int $totalErrors        = 0;
    private static bool $lastRuleHadErrors = false;
    private static float $startTime        = 0;

    public static function setStartTime(float $time): void
    {
        self::$startTime = $time;
    }

    public static function addWarning(string $message): void
    {
        self::$warnings[] = $message;
    }

    public static function addAnonymousError(): void
    {
        self::$totalErrors += 1;
    }

    public static function addRuleError(string $message): void
    {
        self::$errors[]          = $message;
        self::$lastRuleHadErrors = true;
        self::$totalErrors += 1;
    }

    public static function flushWarnings(): array
    {
        $w              = self::$warnings;
        self::$warnings = [];

        return $w;
    }

    public static function flushRuleErrors(): array
    {
        $e                       = self::$errors;
        self::$errors            = [];
        self::$lastRuleHadErrors = false;

        return $e;
    }

    public static function getTotalErrors(): int
    {
        return self::$totalErrors;
    }

    public static function lastRuleHadErrors(): bool
    {
        return self::$lastRuleHadErrors;
    }

    public static function getStartTime(): float
    {
        return self::$startTime;
    }
}
