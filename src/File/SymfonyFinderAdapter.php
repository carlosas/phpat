<?php

declare(strict_types=1);

namespace PhpAT\File;

use Symfony\Component\Finder\Finder as SymfonyFinder;

class SymfonyFinderAdapter implements Finder
{
    private $finder;

    public function __construct(SymfonyFinder $finder)
    {
        $this->finder = $finder;
    }

    public function find(string $filePath, string $fileName, array $include = [], array $exclude = []): array
    {
        $finder = $this->finder->create();

        $finder
            ->in($filePath . '/')
            ->name($fileName)
            ->files()
            ->followLinks()
            ->ignoreUnreadableDirs(true)
            ->ignoreVCS(true);

        $results = $finder->getIterator();
        $results = new PathnameFilterIterator($results, $include, $exclude);

        return iterator_to_array($results);
    }

    public function locateFile(string $filePath, string $fileName): ?\SplFileInfo
    {
        $finder = $this->finder->create();

        $finder
            ->in($filePath . '/')
            ->name($fileName)
            ->files()
            ->depth('== 0');

        if ($finder->hasResults() === false) {
            return null;
        }

        $result = array_values(iterator_to_array($finder->getIterator()));

        return $result[0] ?? null;
    }
}
