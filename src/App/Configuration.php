<?php

declare(strict_types=1);

namespace PhpAT\App;

class Configuration
{
    private $srcPath;
    private $srcIncluded;
    private $srcExcluded;
    private $composerConfiguration;
    private $testsPath;
    private $verbosity;
    private $phpVersion;
    private $ignoreDocBlocks;
    private $ignorePhpExtensions;

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

    private function normalizePath(string $path): string
    {
        return str_replace('\\', '/', realpath($path));
    }
}
