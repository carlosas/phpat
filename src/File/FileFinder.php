<?php
declare(strict_types=1);

namespace PHPArchiTest\File;

use PHPArchiTest\DependencyInjection\Configuration;

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
     * @return \SplFileInfo[]
     */
    public function findOrigin(string $file): array
    {
        $splittedFile = $this->getSplittedFile($file);

        return $this->finder->find(
            $splittedFile[0],
            $splittedFile[1],
            $this->configuration->getOriginIncluded(),
            $this->configuration->getOriginExcluded()
        );
    }

    /**
     * @return \SplFileInfo[]
     */
    public function findDestination(string $file): array
    {
        $splittedFile = $this->getSplittedFile($file);

        return $this->finder->find(
            $splittedFile[0],
            $splittedFile[1],
            $this->configuration->getDestinationIncluded(),
            $this->configuration->getDestinationExcluded()
        );
    }

    private function getSplittedFile(string $file): array
    {
        $file = $this->configuration->getSrcPath().$file;
        $pos = strrpos($file, '/');
        $splittedFile = array(substr($file, 0, $pos), substr($file, $pos + 1));

        return $splittedFile;
    }
}
