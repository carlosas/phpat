<?php

declare(strict_types=1);

namespace PhpAT\Parser;

use PhpAT\Parser\Ast\ComposerModule;

class ComposerFileParser
{
    private const MAIN_SUFFIX = '';
    private const DEV_SUFFIX = '-dev';

    /** @var array */
    private $composerFile;

    /** @var array */
    private $lockFile;

    /** @var array */
    private $lockedPackages;

    public function parse(string $composerFile, string $lockFile): ComposerModule
    {
        $this->composerFile = json_decode(file_get_contents($composerFile), true);
        $this->lockFile = json_decode(file_get_contents($lockFile), true);
        $this->lockedPackages = $this->getPackagesFromLockFile();

        foreach (array_keys($this->getAllPackagesNamespaces()) as $name) {
            $deepDependencies[$name] = $this->getDeepDependenciesFromPackageName($name);
        }

        return new ComposerModule(
            $this->getMainAutoloadNamespaces(),
            $this->getDevAutoloadNamespaces(),
            $this->getMainDirectRequirements(),
            $this->getDevDirectRequirements(),
            $this->getAllPackagesNamespaces(),
            $deepDependencies ?? []
        );
    }

    /**
     * @return ClassLike[]
     */
    public function getMainAutoloadNamespaces(): array
    {
        return $this->convertNamespacesToClassLike(
            $this->extractNamespaces($this->composerFile, self::MAIN_SUFFIX)
        );
    }

    /**
     * @return ClassLike[]
     */
    public function getDevAutoloadNamespaces(): array
    {
        return $this->convertNamespacesToClassLike(
            $this->extractNamespaces($this->composerFile, self::DEV_SUFFIX)
        );
    }

    public function getMainDirectRequirements(): array
    {
        return $this->getRequiredPackageNames($this->composerFile, self::MAIN_SUFFIX);
    }

    public function getDevDirectRequirements(): array
    {
        return $this->getRequiredPackageNames($this->composerFile, self::DEV_SUFFIX);
    }

    public function getMainDeepRequirements(): array
    {
        return $this->getRequiredPackageNames($this->composerFile, self::MAIN_SUFFIX);
    }

    public function getMainNamespacesFromPackageName(string $packageName): array
    {
        return $this->convertNamespacesToClassLike(
            $this->extractNamespaces($this->lockedPackages[$packageName] ?? [], self::MAIN_SUFFIX)
        );
    }

    public function getDeepDependenciesFromPackageName(string $packageName): array
    {
        $dependencies = $this->getPackageDependencies($packageName, self::MAIN_SUFFIX);
        if (empty($dependencies)) {
            return [];
        }

        return $this->flattenDependencies($dependencies, true);
    }

    /**
     * @return ClassLike[]
     */
    public function getMainDirectDependenciesNamespaces(): array
    {
        $requirements = $this->getRequiredPackageNames($this->composerFile, self::MAIN_SUFFIX);
        foreach ($requirements as $packageName) {
            $result[] = $this->getPackageDependencies($packageName, self::MAIN_SUFFIX);
        }

        return $result ?? [];
    }

    private function getMainRequiredPackageNames(): array
    {
        return $this->getRequiredPackageNames($this->composerFile, self::MAIN_SUFFIX);
    }

    private function getDevRequiredPackageNames(): array
    {
        return $this->getRequiredPackageNames($this->composerFile, self::DEV_SUFFIX);
    }

    /**
     * Returns an array of all packages namespaces including deep dependencies (dependencies of dependencies)
     *
     * @return array[]
     */
    public function getAllPackagesNamespaces(): array
    {
        foreach ($this->lockedPackages as $package) {
            $allPackages[$package['name']] = [
                'main' => $this->convertNamespacesToClassLike(
                    $this->extractNamespaces($package, self::MAIN_SUFFIX)
                ),
                'dev' => $this->convertNamespacesToClassLike(
                    $this->extractNamespaces($package, self::DEV_SUFFIX)
                )
            ];
        }

        return $allPackages ?? [];
    }

    /**
     * Returns an array of directly required package names given a package data.
     *
     * @param array $package
     * @param string $keySuffix
     * @return string[]
     */
    private function getRequiredPackageNames(array $package, string $keySuffix): array
    {
        foreach (array_keys($package['require' . $keySuffix] ?? []) as $packageName) {
            $required[] = (string) $packageName;
        }

        return $required ?? [];
    }

    /**
     * Resolves an array of package names to an array of namespaces declared by those packages.
     *
     * @param string[] $requirements
     * @param string $keySuffix
     * @return string[]
     */
    private function autoloadableNamespacesForRequirements(array $requirements, string $keySuffix)
    {
        $namespaces = [];

        foreach ($requirements as $package) {
            $namespaces = array_merge(
                $namespaces,
                $this->extractNamespaces($this->lockedPackages[ $package ], $keySuffix)
            );
        }

        return $namespaces;
    }

    private function getPackageDependencies(string $packageName, string $keySuffix): array
    {
        $package = $this->lockedPackages[$packageName] ?? null;
        $requirements = array_keys($package['require' . $keySuffix] ?? []);

        return $requirements ?? [];
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

    private function extractNamespaces(array $package, string $keySuffix): array
    {
        $namespaces = [];
        foreach (array_keys($package['autoload' . $keySuffix]['psr-0'] ?? []) as $namespace) {
            $namespaces[] = (string) $namespace;
        }
        foreach (array_keys($package['autoload' . $keySuffix]['psr-4'] ?? []) as $namespace) {
            $namespaces[] = (string) $namespace;
        }

        return $namespaces;
    }

    /**
     * @param string[] $namespaces
     * @return ClassLike[]
     */
    private function convertNamespacesToClassLike(array $namespaces): array
    {
        $namespaces = array_filter(
            $namespaces,
            function (string $namespace) {
                return $namespace !== '' || $namespace !== '\\' || $namespace !== '\\\\';
            }
        );

        return array_map(
            function (string $namespace) {
                return new RegexClassName($namespace . '*');
            },
            $namespaces
        );
    }
}
