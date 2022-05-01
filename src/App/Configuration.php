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
    private ?string $generateBaseline;
    private string $rootPath;

    public function __construct(
        string $rootPath,
        string $srcPath,
        array $srcIncluded,
        array $srcExcluded,
        array $composerConfiguration,
        string $testsPath,
        string $baselineFilePath,
        int $verbosity,
        ?string $phpVersion,
        ?string $generateBaseline,
        bool $ignoreDocBlocks,
        bool $ignorePhpExtensions
    ) {
        $this->rootPath              = $rootPath;
        $this->srcPath               = $this->normalizePath($srcPath);
        $this->srcIncluded           = $this->normalizePaths($srcIncluded);
        $this->srcExcluded           = $this->normalizePaths($srcExcluded);
        $this->composerConfiguration = $this->normalizeComposerPaths($composerConfiguration);
        $this->testsPath             = $this->normalizePath($testsPath);
        $this->baselineFilePath      = $this->normalizePath($baselineFilePath);
        $this->verbosity             = $verbosity;
        $this->phpVersion            = $phpVersion;
        $this->generateBaseline      = is_string($generateBaseline) ? $this->normalizePath($generateBaseline) : null;
        $this->ignoreDocBlocks       = $ignoreDocBlocks;
        $this->ignorePhpExtensions   = $ignorePhpExtensions;
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

    public function getGenerateBaseline(): ?string
    {
        return $this->generateBaseline;
    }

    /**
     * @param array<string, array{json: null|string, lock?: null|string}> $composerConfig
     * @return array<string, array{json: null|string, lock?: null|string}>
     */
    private function normalizeComposerPaths(array $composerConfig): array
    {
        $result = [];
        foreach ($composerConfig as $packageName => $packageConfig) {
            $result[$packageName]['json'] = $this->normalizePath($packageConfig['json']);
            $result[$packageName]['lock'] = $this->normalizePath($packageConfig['lock']);
        }

        return $result;
    }

    /**
     * @param array<null|string> $path
     * @return array<null|string>
     */
    private function normalizePaths(array $path): array
    {
        return array_map(function (string $path) {
            return $this->normalizePath($path);
        }, $path);
    }

    private function normalizePath(?string $path): ?string
    {
        if ($path === null) {
            return null;
        }

        $path = $this->rootPath . '/' . $path;
        if (is_file($path)) {
            $path = realpath($path);
        }

        return str_replace('\\', '/', $path);
    }
}
