<?php declare(strict_types=1);

namespace PhpAT\File;

use PhpAT\DependencyInjection\Configuration;

class FileFinder
{
    private $finder;
    private $configuration;

    public function __construct(Finder $finder, Configuration $configuration)
    {
        $this->finder = $finder;
        $this->configuration = $configuration;
    }

    /**
     * @param string $file
     * @param array  $excluded
     * @return \SplFileInfo[]
     */
    public function findFiles(string $file, array $excluded = []): array
    {
        $splittedFile = $this->getSplittedFile($file);

        return $this->finder->find(
            $splittedFile[0],
            $splittedFile[1],
            $this->configuration->getSrcIncluded(),
            array_merge($excluded, $this->configuration->getSrcExcluded())
        );
    }

    private function getSplittedFile(string $file): array
    {
        $file = $this->configuration->getSrcPath() . $file;
        $pos = strrpos($file, '/');
        $splittedFile = array(substr($file, 0, $pos), substr($file, $pos + 1));

        return $splittedFile;
    }
}
