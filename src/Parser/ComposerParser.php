<?php

declare(strict_types=1);

namespace PhpAT\Parser;

use PhpAT\App\Configuration;
use PhpAT\File\FileFinder;

class ComposerParser
{
    private FileFinder $finder;
    private Configuration $configuration;

    public function __construct(FileFinder $finder, Configuration $configuration)
    {
        $this->finder        = $finder;
        $this->configuration = $configuration;
    }

    /**
     * @throws \Exception
     * @return array<string>
     */
    public function getFilesToAutoload(string $composerPackage, bool $includeDev): array
    {
        $composerPath = $this->configuration->getComposerConfiguration()[$composerPackage]['json'] ?? null;
        if (!is_file($composerPath)) {
            throw new \Exception('Composer file ' . $composerPath . ' not found.');
        }

        $composer   = json_decode(file_get_contents($composerPath), true);
        $filesFound = [];
        foreach ($composer['autoload']['classmap'] ?? [] as $path) {
            array_push($filesFound, ...$this->getFiles($path));
        }
        foreach ($composer['autoload']['files'] ?? [] as $path) {
            array_push($filesFound, ...$this->getFiles($path));
        }
        foreach ($composer['autoload']['psr-0'] ?? [] as $path) {
            array_push($filesFound, ...$this->getFiles($path));
        }
        foreach ($composer['autoload']['psr-4'] ?? [] as $path) {
            array_push($filesFound, ...$this->getFiles($path));
        }
        if ($includeDev) {
            foreach ($composer['autoload-dev']['classmap'] ?? [] as $path) {
                array_push($filesFound, ...$this->getFiles($path));
            }
            foreach ($composer['autoload-dev']['files'] ?? [] as $path) {
                array_push($filesFound, ...$this->getFiles($path));
            }
            foreach ($composer['autoload-dev']['psr-0'] ?? [] as $path) {
                array_push($filesFound, ...$this->getFiles($path));
            }
            foreach ($composer['autoload-dev']['psr-4'] ?? [] as $path) {
                array_push($filesFound, ...$this->getFiles($path));
            }
        }
        foreach ($composer['autoload']['exclude-from-classmap'] ?? [] as $path) {
            $filesFound = array_diff($filesFound, $this->getFiles($path));
        }

        return array_unique(array_map(fn (\SplFileInfo $i) => $i->getPathname(), $filesFound));
    }

    /**
     * @return array<\SplFileInfo>
     */
    private function getFiles(string $path): array
    {
        if (is_file($path)) {
            $sfi = $this->finder->findFile($path, $this->configuration->getParserExclude());
            return ($sfi === null) ? [] : [$sfi];
        }

        return array_values($this->finder->findPhpFilesInPath($path, $this->configuration->getParserExclude()));
    }
}
