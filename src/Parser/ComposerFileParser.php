<?php

declare(strict_types=1);

namespace PhpAT\Parser;

class ComposerFileParser
{

    /** @var string */
    private $composerFilePath;

    /** @var array */
    private $composerFile;

    /** @var string */
    private $lockFilePath;

    /** @var array */
    private $lockFile;

    /** @var array */
    private $lockedPackages;

    public function __construct(string $composerFile, string $lockFile = null)
    {
        if ($lockFile === null) {
            $lockFile = substr($composerFile, 0, -5) . '.lock';
        }

        $this->composerFile = json_decode(file_get_contents($composerFile), true);
        $this->composerFilePath = $composerFile;
        $this->lockFile = json_decode(file_get_contents($lockFile), true);
        $this->lockFilePath = $lockFile;
        $this->lockedPackages = $this->getPackagesFromLockFile();
    }

    /**
     * Returns an array of all namespaces declared by the current composer file.
     *
     * @param bool $includeDev
     * @return string[]
     */
    public function getNamespaces(bool $includeDev = false): array
    {
        return $this->extractNamespaces($this->composerFile, $includeDev);
    }

    /**
     * Returns an array of all required namespaces including deep dependencies (dependencies of dependencies)
     *
     * @param bool $includeDev
     * @return string[]
     */
    public function getDeepRequirementNamespaces(bool $includeDev): array
    {
        $required = $this->getDirectDependencies($includeDev);
        $required = $this->flattenDependencies($required, $includeDev);
        return $this->autoloadableNamespacesForRequirements($required, $includeDev);
    }

    /**
     * Returns an array of directly required package names.
     *
     * @param bool $includeDev
     * @return string[]
     */
    public function getDirectDependencies(bool $includeDev): array
    {
        $required = [];
        foreach (array_keys($this->composerFile['require'] ?? []) as $packageName) {
            $required[] = (string) $packageName;
        }

        if ($includeDev) {
            foreach (array_keys($this->composerFile['require-dev'] ?? []) as $packageName) {
                $required[] = (string) $packageName;
            }
        }

        return $required;
    }

    /**
     * Resolves an array of package names to an array of namespaces declared by those packages.
     *
     * @param string[] $requirements
     * @param bool $includeDev
     * @return string[]
     */
    public function autoloadableNamespacesForRequirements(array $requirements, bool $includeDev)
    {
        $namespaces = [];

        foreach ($requirements as $package) {
            $namespaces = array_merge(
                $namespaces,
                $this->extractNamespaces($this->lockedPackages[ $package ], $includeDev)
            );
        }

        return $namespaces;
    }

    public function getComposerFilePath(): string
    {
        return $this->composerFilePath;
    }

    public function getLockFilePath(): string
    {
        return $this->lockFilePath;
    }

    private function flattenDependencies(array $topLevelRequirements, bool $includeDev): array
    {
        $required = [];
        $toCheck = $topLevelRequirements;

        while (\count($toCheck) > 0) {
            $packageName = array_pop($toCheck);
            $package = $this->lockedPackages[ $packageName ] ?? null;
            if ($package === null) {
                continue;
            }

            $required[] = $packageName;

            $deepRequirements = array_keys($package['require'] ?? []);
            if ($includeDev) {
                $deepRequirements = array_merge(
                    $deepRequirements,
                    array_keys($package['require-dev'] ?? [])
                );
            }

            foreach ($deepRequirements as $name) {
                if (!\in_array($name, $required)) {
                    $toCheck[] = $name;
                }
            }
        }

        return $required;
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

    private function extractNamespaces(array $package, bool $includeDev): array
    {
        $namespaces = [];
        foreach (array_keys($package['autoload']['psr-0'] ?? []) as $namespace) {
            $namespaces[] = (string) $namespace;
        }
        foreach (array_keys($package['autoload']['psr-4'] ?? []) as $namespace) {
            $namespaces[] = (string) $namespace;
        }

        if ($includeDev) {
            foreach (array_keys($package['autoload-dev']['psr-0'] ?? []) as $namespace) {
                $namespaces[] = (string) $namespace;
            }
            foreach (array_keys($package['autoload-dev']['psr-4'] ?? []) as $namespace) {
                $namespaces[] = (string) $namespace;
            }
        }

        return $namespaces;
    }
}
