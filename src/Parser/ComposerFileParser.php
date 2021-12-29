<?php

declare(strict_types=1);

namespace PhpAT\Parser;

class ComposerFileParser
{
    private array $composerFile;
    private array $lockFile;
    private ?array $lockedPackages = null;

    /**
     * @throws \Exception
     */
    public function parse(string $composerFilePath, string $lockFilePath): self
    {
        $this->composerFile   = json_decode(file_get_contents($composerFilePath), true);
        $this->lockFile       = json_decode(file_get_contents($lockFilePath), true);
        $this->lockedPackages = $this->getPackagesFromLockFile();

        return $this;
    }

    /**
     * Returns an array of all namespaces declared by the current composer file.
     *
     * @return array<string>
     */
    public function getNamespaces(bool $dev = false): array
    {
        return $this->extractNamespaces($this->composerFile, $dev);
    }

    /**
     * Returns an array of all required namespaces including deep dependencies (dependencies of dependencies)
     *
     * @return array<string>
     */
    public function getDeepRequirementNamespaces(bool $dev): array
    {
        $required = $this->getDirectDependencies($dev);
        $required = $this->flattenDependencies($required);
        return $this->autoloadableNamespacesForRequirements($required);
    }

    /**
     * Returns an array of directly required package names.
     *
     * @return array<string>
     */
    public function getDirectDependencies(bool $dev): array
    {
        $required = [];
        $key      = $dev ? 'require-dev' : 'require';
        foreach (array_keys($this->composerFile[$key] ?? []) as $packageName) {
            $required[] = (string) $packageName;
        }

        return $required;
    }

    /**
     * Resolves an array of package names to an array of namespaces declared by those packages.
     *
     * @param array<string> $requirements
     * @return array<string>
     */
    public function autoloadableNamespacesForRequirements(array $requirements)
    {
        foreach ($requirements as $package) {
            $n = $this->extractNamespaces($this->lockedPackages[$package], false);
            foreach ($n as $k => $v) {
                if (empty($v)) {
                    unset($n[$k]);
                }
            }
            $namespaces = array_merge($namespaces ?? [], $n);
        }

        return $namespaces ?? [];
    }

    private function flattenDependencies(array $topLevelRequirements): array
    {
        $required = [];
        $toCheck  = $topLevelRequirements;

        while ($toCheck !== []) {
            $packageName = array_pop($toCheck);
            $package     = $this->lockedPackages[ $packageName ] ?? null;
            if ($package === null) {
                continue;
            }

            $required[] = $packageName;

            $deepRequirements = array_keys($package['require'] ?? []);

            foreach ($deepRequirements as $name) {
                if (!\in_array($name, $required, true)) {
                    $toCheck[] = $name;
                }
            }
        }

        return array_unique($required);
    }

    private function getPackagesFromLockFile(): array
    {
        $lockedPackages = [];

        foreach ($this->lockFile['packages'] ?? [] as $package) {
            $lockedPackages[ $package['name'] ] = $package;
        }

        foreach ($this->lockFile['packages-dev'] ?? [] as $package) {
            $lockedPackages[ $package['name'] ] = $package;
        }

        return $lockedPackages;
    }

    private function extractNamespaces(array $package, bool $dev): array
    {
        $key        = $dev ? 'autoload-dev' : 'autoload';
        $namespaces = [];
        foreach (array_keys($package[$key]['psr-0'] ?? []) as $namespace) {
            $namespaces[] = (string) $namespace;
        }
        foreach (array_keys($package[$key]['psr-4'] ?? []) as $namespace) {
            $namespaces[] = (string) $namespace;
        }

        return $namespaces;
    }
}
