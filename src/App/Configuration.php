<?php

declare(strict_types=1);

namespace PhpAT\App;

class Configuration
{
    private $phpStormStubsPath;
    private $srcPath;
    private $srcIncluded;
    private $srcExcluded;
    private $composerConfiguration;
    private $testsPath;
    private $verbosity;
    private $dryRun;
    private $ignoreDocBlocks;
    private $ignorePhpExtensions;

    public function __construct(array $config)
    {
        $this->srcPath = $config['src']['path'] ?? '';
        $this->srcIncluded = $config['src']['include'] ?? [];
        $this->srcExcluded = $config['src']['exclude'] ?? [];
        $this->composerConfiguration = $config['composer'] ?? [];
        $this->testsPath = $config['tests']['path'] ?? '';
        $this->verbosity = (int) ($config['options']['verbosity'] ?? 1);
        $this->dryRun = (bool) ($config['options']['dry-run'] ?? false);
        $this->ignoreDocBlocks = (bool) ($config['options']['ignore_docblocks'] ?? false);
        $this->ignorePhpExtensions = (bool) ($config['options']['ignore_php_extensions'] ?? true);
        $this->phpStormStubsPath = is_file(__DIR__ . '/../../../../autoload.php')
            ? __DIR__ . '/../../../../jetbrains/phpstorm-stubs'
            : __DIR__ . '/../../vendor/jetbrains/phpstorm-stubs';
    }

    public function getSrcPath(): string
    {
        return $this->srcPath;
    }

    public function getSrcIncluded(): array
    {
        return $this->srcIncluded;
    }

    public function getSrcExcluded(): array
    {
        return $this->srcExcluded;
    }

    public function getComposerConfiguration(): array
    {
        return $this->composerConfiguration;
    }

    public function getTestsPath(): string
    {
        return $this->testsPath;
    }

    public function getVerbosity(): int
    {
        return $this->verbosity;
    }

    public function getDryRun(): bool
    {
        return $this->dryRun;
    }

    public function getIgnoreDocBlocks(): bool
    {
        return $this->ignoreDocBlocks;
    }

    public function getIgnorePhpExtensions(): bool
    {
        return $this->ignorePhpExtensions;
    }

    public function getPhpStormStubsPath(): string
    {
        return $this->phpStormStubsPath;
    }
}
