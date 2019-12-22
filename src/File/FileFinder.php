<?php

declare(strict_types=1);

namespace PhpAT\File;

use PhpAT\App\Configuration;

class FileFinder
{
    private $finder;

    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
    }

    /**
     * @param  string $file
     * @param  array  $excluded
     * @return \SplFileInfo[]
     */
    public function findFiles(string $file, array $excluded = []): array
    {
        $splittedFile = $this->getSplittedFile($file);

        return $this->finder->find(
            $splittedFile[0],
            $splittedFile[1],
            [],
            array_merge($excluded, Configuration::getSrcExcluded())
        );
    }

    public function findAllFiles(string $path): array
    {
        //array_merge(Configuration::getSrcExcluded())
        return $this->finder->find($path, '*.php', [], []);
    }

    private function getSplittedFile(string $file): array
    {
        $file = Configuration::getSrcPath() . $file;
        $pos = strrpos($file, '/');
        $splittedFile = [substr($file, 0, $pos), substr($file, $pos + 1)];

        return $splittedFile;
    }
}
