<?php

declare(strict_types=1);

namespace PhpAT\Parser;

use PhpAT\App\Configuration;
use PhpAT\File\FileFinder;
use PhpAT\Parser\Ast\ClassLike;
use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Ast\RegexClassName;
use PhpAT\Parser\Parser\TinyParser;

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
    public function getAutoloadFiles(string $composerPackageName, bool $dev): array
    {
        $composer = $this->getComposerContent($composerPackageName);

        return array_unique(
            array_map(
                fn (\SplFileInfo $i) => $i->getPathname(),
                $this->extractAllAutoloadFiles($dev ? $composer['autoload-dev'] : $composer['autoload'])
            )
        );
    }

    /**
     * @throws \Exception
     * @return array<ClassLike>
     */
    public function getAutoloadNamespaces(string $composerPackageName, bool $dev): array
    {
        $composer = $this->getComposerContent($composerPackageName);

        $found = $this->extractAutoloadNamespacesOrFiles($dev ? $composer['autoload-dev'] : $composer['autoload']);
        $names = $this->convertToRegexClassNames($found['namespaces']);

        foreach ($found['files'] as $file) {
            $parser = new TinyParser();
            array_push($names, ...$this->convertToFullClassNames(array_keys($parser->parse($file))));
        }

        return $names;
    }

    private function getComposerContent(string $composerPackageName): array
    {
        $composerPath = $this->configuration->getComposerConfiguration()[$composerPackageName]['json'] ?? null;
        if (!is_file($composerPath)) {
            throw new \Exception('Composer file ' . $composerPath . ' not found.');
        }

        return json_decode(file_get_contents($composerPath), true);
    }

    /**
     * @param array<string, array<string>> $autoloadSection
     * @return array<\SplFileInfo>
     */
    private function extractAllAutoloadFiles(array $autoloadSection): array
    {
        $filesFound = [];
        foreach ($autoloadSection['classmap'] ?? [] as $path) {
            array_push($filesFound, ...$this->getFiles($path));
        }
        foreach ($autoloadSection['files'] ?? [] as $path) {
            array_push($filesFound, ...$this->getFiles($path));
        }
        foreach ($autoloadSection['psr-0'] ?? [] as $path) {
            array_push($filesFound, ...$this->getFiles($path));
        }
        foreach ($autoloadSection['psr-4'] ?? [] as $path) {
            array_push($filesFound, ...$this->getFiles($path));
        }
        foreach ($autoloadSection['exclude-from-classmap'] ?? [] as $path) {
            $filesFound = array_diff($filesFound, $this->getFiles($path));
        }

        return $filesFound;
    }

    /**
     * @param array<string, array<string>> $autoloadSection
     * @return array<string, array>
     */
    private function extractAutoloadNamespacesOrFiles(array $autoloadSection): array
    {
        $filesFound      = [];
        $namespacesFound = [];
        foreach ($autoloadSection['classmap'] ?? [] as $path) {
            array_push($filesFound, ...$this->getFiles($path));
        }
        foreach ($autoloadSection['files'] ?? [] as $path) {
            array_push($filesFound, ...$this->getFiles($path));
        }
        foreach (
            array_merge($autoloadSection['psr-0'] ?? [], $autoloadSection['psr-4'] ?? [])
            as $namespace => $path
        ) {
            if ($namespace === '') {
                array_push($filesFound, ...$this->getFiles($path));
            } else {
                array_push($namespacesFound, $namespace);
            }
        }
        foreach ($autoloadSection['exclude-from-classmap'] ?? [] as $path) {
            $filesFound = array_diff($filesFound, $this->getFiles($path));
        }

        return [
            'namespaces' => $namespacesFound,
            'files'      => $filesFound
        ];
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

    /**
     * @param array<string> $names
     * @return array<RegexClassName>
     */
    private function convertToRegexClassNames(array $names): array
    {
        foreach ($names as $name) {
            if (substr($name, -1) === '\\') {
                $r[] = new RegexClassName($name . '*');
            } else {
                $r[] = new RegexClassName($name . '\*');
            }
        }

        return $r ?? [];
    }

    /**
     * @param array<string> $names
     * @return array<FullClassName>
     */
    private function convertToFullClassNames(array $names): array
    {
        return array_map(
            fn (string $n) => new FullClassName($n),
            $names
        );
    }
}
