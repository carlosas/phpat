<?php

declare(strict_types=1);

namespace PhpAT\App;

class Configuration
{
    private static $phpStormStubsPath;
    private static $initialized = false;
    private static $srcPath;
    private static $srcIncluded;
    private static $srcExcluded;
    private static $testsPath;
    private static $verbosity;
    private static $dryRun;
    private static $ignoreDocBlocks;
    private static $ignorePhpExtensions;

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
        self::$ignoreDocBlocks = (bool) ($config['options']['ignore_docblocks'] ?? false);
        self::$ignorePhpExtensions = (bool) ($config['options']['ignore_php_extensions'] ?? true);
        self::$phpStormStubsPath = is_file(__DIR__ . '/../../../../autoload.php')
            ? __DIR__ . '/../../../../jetbrains/phpstorm-stubs'
            : __DIR__ . '/../../vendor/jetbrains/phpstorm-stubs';
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

    public static function getIgnoreDocBlocks(): bool
    {
        return self::$ignoreDocBlocks;
    }

    public static function getIgnorePhpExtensions(): bool
    {
        return self::$ignorePhpExtensions;
    }

    public static function getPhpStormStubsPath(): string
    {
        return self::$phpStormStubsPath;
    }
}
