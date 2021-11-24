<?php

declare(strict_types=1);

namespace PhpAT\Parser;

class ComposerFileParser
{
    private ?string $composerFilePath = null;
    /** @var array */
    private $composerFile;
    private ?string $lockFilePath = null;
    /** @var array */
    private $lockFile;
    private ?array $lockedPackages = null;

    /**
     * @throws \Exception
     */
    public function parse(string $composerFilePath, string $lockFilePath): self
    {
        $this->composerFilePath = $composerFilePath;
        $this->composerFile = json_decode(file_get_contents($this->composerFilePath), true);
        $this->lockFilePath = $lockFilePath;
        $this->lockFile = json_decode(file_get_contents($this->lockFilePath), true);
        $this->lockedPackages = $this->getPackagesFromLockFile();

        return $this;
    }

    /**
     * Returns an array of all namespaces declared by the current composer file.
     *
     * @return string[]
     */
    public function getNamespaces(bool $dev = false): array
    {
        return $this->extractNamespaces($this->composerFile, $dev);
    }

    /**
     * Returns an array of all required namespaces including deep dependencies (dependencies of dependencies)
     *
     * @return string[]
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
     * @return string[]
     */
    public function getDirectDependencies(bool $dev): array
    {
        $required = [];
        $key = $dev ? 'require-dev' : 'require';
        foreach (array_keys($this->composerFile[$key] ?? []) as $packageName) {
            $required[] = (string) $packageName;
        }

        return $required;
    }

    /**
     * Resolves an array of package names to an array of namespaces declared by those packages.
     *
     * @param string[] $requirements
     * @return string[]
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

    public function getComposerFilePath(): string
    {
        return $this->composerFilePath;
    }

    public function getLockFilePath(): string
    {
        return $this->lockFilePath;
    }

    private function flattenDependencies(array $topLevelRequirements): array
    {
        $required = [];
        $toCheck = $topLevelRequirements;

        while ($toCheck !== []) {
            $packageName = array_pop($toCheck);
            $package = $this->lockedPackages[ $packageName ] ?? null;
            if ($package === null) {
                continue;
            }

            $required[] = $packageName;

            $deepRequirements = array_keys($package['require'] ?? []);

            foreach ($deepRequirements as $name) {
                if (!\in_array($name, $required)) {
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
        $key = $dev ? 'autoload-dev' : 'autoload';
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
