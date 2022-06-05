<?php

declare(strict_types=1);

namespace PHPatOld\File;

use PHPatOld\App\Configuration;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;

class FileFinder
{
    private Finder $finder;
    private Configuration $configuration;

    public function __construct(Finder $finder, Configuration $configuration)
    {
        $this->finder        = $finder;
        $this->configuration = $configuration;
    }

    public function findFile(string $file): ?\SplFileInfo
    {
        if (!file_exists($file)) {
            return null;
        }

        $parts = $this->splitFile($file);

        return ($this->finder->locateFile($parts[0], $parts[1])) ?? null;
    }

    /**
     * @return array<\SplFileInfo>
     */
    public function findSrcFiles(string $file, array $excluded = []): array
    {
        $parts = $this->splitFile($this->configuration->getSrcPath() . '/' . $file);

        try {
            return $this->finder->find($parts[0], $parts[1], [], $excluded);
        } catch (DirectoryNotFoundException $e) {
            return [];
        }
    }

    /**
     * @return array<\SplFileInfo>
     */
    public function findPhpFilesInPath(string $path, array $excluded = []): array
    {
        try {
            return $this->finder->find($path, '*.php', [], $excluded);
        } catch (DirectoryNotFoundException $e) {
            return [];
        }
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
