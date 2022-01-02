<?php

declare(strict_types=1);

namespace PhpAT\File;

use Symfony\Component\Finder\Finder as SymfonyFinder;

class SymfonyFinderAdapter implements Finder
{
    private SymfonyFinder $finder;

    public function __construct(SymfonyFinder $finder)
    {
        $this->finder = $finder;
    }

    public function findFiles(string $filePath, string $fileName, array $exclude = []): array
    {
        $finder = $this->finder->create();

        $finder
            ->in($this->normalize($filePath))
            ->name($fileName)
            ->files()
            ->followLinks()
            ->ignoreUnreadableDirs(true)
            ->ignoreVCS(true);

        $results = new PathnameFilterIterator($finder->getIterator(), $exclude);

        return iterator_to_array($results);
    }

    public function locateFile(string $filePath, string $fileName, array $exclude = []): ?\SplFileInfo
    {
        $finder = $this->finder->create();

        $finder
            ->in($this->normalize($filePath))
            ->name($fileName)
            ->files()
            ->depth('== 0');

        if (!$finder->hasResults()) {
            return null;
        }

        $results = new PathnameFilterIterator($finder->getIterator(), $exclude);

        return array_values(iterator_to_array($results))[0] ?? null;
    }

    private function normalize(string $filePath): string
    {
        if (substr($filePath, -2) === '/*') {
            $filePath = rtrim($filePath, '*');
        }
        if (substr($filePath, -1) === '/') {
            $filePath = rtrim($filePath, '/');
        }

        return $filePath;
    }
}
