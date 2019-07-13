<?php

namespace PHPArchiTest\File;

class SymfonyFinderAdapter implements Finder
{
    private $finder;

    public function __construct(\Symfony\Component\Finder\Finder $finder)
    {
        $this->finder = $finder;
    }

    public function find(string $filePath, string $fileName, array $include = [], array $exclude = []): array
    {
        $finder = $this->finder->create();

        $finder
            ->in($filePath.'/')
            ->name($fileName)
            ->files()
            ->followLinks()
            ->ignoreUnreadableDirs(true)
            ->ignoreVCS(true);

        $results = $finder->getIterator();
        $results = new PathnameFilterIterator($results, $include, $exclude);

        return iterator_to_array($results);
    }
}
