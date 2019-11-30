<?php

declare(strict_types=1);

namespace PhpAT\App;

class Configuration
{
    private static $initialized = false;
    private static $srcPath;
    private static $srcIncluded;
    private static $srcExcluded;
    private static $testsPath;
    private static $verbosity;
    private static $dryRun;
    private static $dependencyIgnoreDocBlocks;

    public static function init(array $config)
    {
        if (!self::$initialized) {
            self::process($config);
        }
    }

    private static function process(array $config)
    {
        self::$srcPath = $config['src']['path'] ?? '';
        self::$srcIncluded = $config['src']['include'] ?? [];
        self::$srcExcluded = $config['src']['exclude'] ?? [];
        self::$testsPath = $config['tests']['path'] ?? '';
        self::$verbosity = (int) ($config['options']['verbosity'] ?? 1);
        self::$dryRun = (bool) ($config['options']['dry-run'] ?? false);
        self::$dependencyIgnoreDocBlocks = (bool) ($config['options']['dependency']['ignore_docblocks'] ?? false);
    }

    public static function getSrcPath(): string
    {
        return self::$srcPath;
    }

    public static function getSrcIncluded(): array
    {
        return self::$srcIncluded;
    }

    public static function getSrcExcluded(): array
    {
        return self::$srcExcluded;
    }

    public static function getTestsPath(): string
    {
        return self::$testsPath;
    }

    public static function getVerbosity(): int
    {
        return self::$verbosity;
    }

    public static function getDryRun(): bool
    {
        return self::$dryRun;
    }

    public static function getDependencyIgnoreDocBlocks(): bool
    {
        return self::$dependencyIgnoreDocBlocks;
    }
}
