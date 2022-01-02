<?php

declare(strict_types=1);

namespace PhpAT\App;

class Configuration
{
    private array $parserInclude;
    private array $parserExclude;
    private array $composerConfiguration;
    private string $testsPath;
    private int $verbosity;
    private ?string $phpVersion;
    private bool $parseDevFiles;
    private bool $ignoreDocBlocks;
    private bool $ignorePhpExtensions;
    private string $baselineFilePath;
    private ?string $generateBaseline;
    private string $rootPath;

    public function __construct(
        array $parserInclude,
        array $parserExcluded,
        array $composerConfiguration,
        string $testsPath,
        string $baselineFilePath,
        int $verbosity,
        ?string $phpVersion,
        ?string $generateBaseline,
        bool $parseDevFiles,
        bool $ignoreDocBlocks,
        bool $ignorePhpExtensions
    ) {
        $this->rootPath = $this->buildRootPath();

        $this->parserInclude         = $parserInclude;
        $this->parserExclude         = $parserExcluded;
        $this->composerConfiguration = $composerConfiguration;
        $this->testsPath             = $testsPath;
        $this->baselineFilePath      = $this->normalizePath($baselineFilePath);
        $this->verbosity             = $verbosity;
        $this->phpVersion            = $phpVersion;
        $this->generateBaseline      = is_string($generateBaseline) ? $this->normalizePath($generateBaseline) : null;
        $this->parseDevFiles         = $parseDevFiles;
        $this->ignoreDocBlocks       = $ignoreDocBlocks;
        $this->ignorePhpExtensions   = $ignorePhpExtensions;
    }

    public function getRootPath(): string
    {
        return $this->rootPath;
    }

    public function getParserInclude(): array
    {
        return $this->parserInclude;
    }

    public function getParserExclude(): array
    {
        return $this->parserExclude;
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

    public function getParseDevFiles(): bool
    {
        return $this->parseDevFiles;
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

    private function buildRootPath(): string
    {
        $path = is_file(__DIR__ . '/../../../../autoload.php')
            ? realpath(__DIR__ . '/../../../../..')
            : realpath(__DIR__ . '/../..');

        if ($path === false) {
            throw new \Exception('Unable to find your autoload.php file');
        }

        return $path;
    }
}
