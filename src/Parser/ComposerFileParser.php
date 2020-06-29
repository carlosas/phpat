<?php

declare(strict_types=1);

namespace PhpAT\Parser;

use PhpAT\App\Configuration;

class ComposerFileParser
{
    /** @var string */
    private $composerFilePath;
    /** @var array */
    private $composerFile;
    /** @var string */
    private $lockFilePath;
    /** @var array */
    private $lockFile = null;
    /** @var array */
    private $lockedPackages;
    /** @var array */
    private $configuration;

    /**
     * @param Configuration $configuration
     * @param string        $packageAlias
     * @return $this
     * @throws \Exception
     */
    public function parse(Configuration $configuration, string $packageAlias): self
    {
        $this->configuration = $configuration->getComposerConfiguration();

        if (!isset($this->configuration[$packageAlias]['json'])) {
            throw new \Exception('Composer package "' . $packageAlias . '" is not properly configured');
        }

        $this->composerFilePath = $this->configuration[$packageAlias]['json'];
        $this->composerFile = json_decode(file_get_contents($this->composerFilePath), true);
        $this->lockFilePath = $this->configuration[$packageAlias]['lock']
            ?? substr($this->composerFilePath, 0, -5) . '.lock';
        $this->lockFile = json_decode(file_get_contents($this->lockFilePath), true);
        $this->lockedPackages = $this->getPackagesFromLockFile();

        return $this;
    }

    /**
     * Returns an array of all namespaces declared by the current composer file.
     *
     * @param bool $dev
     * @return string[]
     */
    public function getNamespaces(bool $dev = false): array
    {
        return $this->extractNamespaces($this->composerFile, $dev);
    }

    /**
     * Returns an array of all required namespaces including deep dependencies (dependencies of dependencies)
     *
     * @param bool $dev
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
     * @param bool $dev
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
        $namespaces = [];

        foreach ($requirements as $package) {
            $namespaces = array_merge(
                $namespaces,
                $this->extractNamespaces($this->lockedPackages[ $package ], false)
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

    private function flattenDependencies(array $topLevelRequirements): array
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
