<?php

declare(strict_types=1);

namespace PhpAT\File;

use SplFileInfo;
use PhpAT\App\Configuration;

class FileFinder
{
    /** @var Finder */
    private $finder;
    /** @var Configuration */
    private $configuration;

    public function __construct(Finder $finder, Configuration $configuration)
    {
        $this->finder = $finder;
        $this->configuration = $configuration;
    }

    public function findFile(string $file): ?SplFileInfo
    {
        if (!file_exists($file)) {
            return null;
        }

        $parts = $this->splitFile($file);

        return ($this->finder->locateFile($parts[0], $parts[1])) ?? null;
    }

    /**
     * @param  string $file
     * @param  array  $excluded
     * @return \SplFileInfo[]
     */
    public function findSrcFiles(string $file, array $excluded = []): array
    {
        $parts = $this->splitFile($this->configuration->getSrcPath() . $file);

        return $this->finder->find(
            $parts[0],
            $parts[1],
            [],
            $excluded
        );
    }

    public function findPhpFilesInPath(string $path): array
    {
        return $this->finder->find($path, '*.php', [], []);
    }

    private function splitFile(string $file): ?array
    {
        $pos = strrpos($file, '/');
        if ($pos === false) {
            return null;
        }

        return [substr($file, 0, $pos), substr($file, $pos + 1)];
    }
}
