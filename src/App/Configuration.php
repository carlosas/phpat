<?php

declare(strict_types=1);

namespace PhpAT\App;

class Configuration
{
    private string $srcPath;
    private array $srcIncluded;
    private array $srcExcluded;
    private array $composerConfiguration;
    private string $testsPath;
    private int $verbosity;
    private ?string $phpVersion;
    private bool $ignoreDocBlocks;
    private bool $ignorePhpExtensions;
    private string $baselineFilePath;

    public function __construct(
        string $srcPath,
        array $srcIncluded,
        array $srcExcluded,
        array $composerConfiguration,
        string $testPath,
        int $verbosity,
        ?string $phpVersion,
        bool $ignoreDocBlocks,
        bool $ignorePhpExtensions
    ) {
        $root = is_file(__DIR__ . '/../../../../autoload.php')
            ? realpath(__DIR__ . '/../../../../..')
            : realpath(__DIR__ . '/../..');

        $this->srcPath = $this->normalizePath($root . '/' . $srcPath);
        $this->srcIncluded = $srcIncluded;
        $this->srcExcluded = $srcExcluded;
        $this->composerConfiguration = $composerConfiguration;
        $this->testsPath = $testPath;
        $this->verbosity = $verbosity;
        $this->phpVersion = $phpVersion;
        $this->ignoreDocBlocks = $ignoreDocBlocks;
        $this->ignorePhpExtensions = $ignorePhpExtensions;
        $this->baselineFilePath = $this->normalizePath($root . '/phpat.baseline.json');
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

    public function getPhpVersion(): ?string
    {
        return $this->phpVersion;
    }

    public function getIgnoreDocBlocks(): bool
    {
        return $this->ignoreDocBlocks;
    }

    public function getIgnorePhpExtensions(): bool
    {
        return $this->ignorePhpExtensions;
    }

    public function getBaselineFilePath(): string
    {
        return $this->baselineFilePath;
    }

    private function normalizePath(string $path): string
    {
        if (is_file($path)) {
            $path = realpath($path);
        }

        return str_replace('\\', '/', $path);
    }
}
