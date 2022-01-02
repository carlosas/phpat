<?php

declare(strict_types=1);

namespace PhpAT\File;

use Symfony\Component\Finder\Exception\DirectoryNotFoundException;

class FileFinder
{
    private Finder $finder;

    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
    }

    public function findFile(string $file, array $excluded = []): ?\SplFileInfo
    {
        if (!file_exists($file)) {
            return null;
        }

        $parts = $this->splitFile($file);

        return $this->finder->locateFile($parts[0], $parts[1], $excluded);
    }

    /**
     * @return array<\SplFileInfo>
     */
    public function findPhpFilesInPath(string $path, array $excluded = []): array
    {
        try {
            return $this->finder->findFiles($path, '*.php', $excluded);
        } catch (DirectoryNotFoundException $e) {
            return [];
        }
    }

    /**
     * @return array<string>
     */
    private function splitFile(string $file): array
    {
        $pos = strrpos($file, '/');
        if ($pos === false) {
            return ['./', $file];
        }

        return [substr($file, 0, $pos), substr($file, $pos + 1)];
    }
}
