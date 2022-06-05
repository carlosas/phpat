<?php

namespace PHPatOld\Parser\Ast;

class ComposerPackage
{
    private string $packageAlias;
    /** @var array<ClassLike> */
    private array $autoload;
    /** @var array<ClassLike> */
    private array $devAutoload;
    /** @var array<ClassLike> */
    private array $dependencies;
    /** @var array<ClassLike> */
    private array $devDependencies;

    /**
     * ComposerPackage constructor.
     * @param array<ClassLike> $autoload
     * @param array<ClassLike> $devAutoload
     * @param array<ClassLike> $dependencies
     * @param array<ClassLike> $devDependencies
     */
    public function __construct(
        string $packageAlias,
        array $autoload,
        array $devAutoload,
        array $dependencies,
        array $devDependencies
    ) {
        $this->packageAlias    = $packageAlias;
        $this->autoload        = $autoload;
        $this->devAutoload     = $devAutoload;
        $this->dependencies    = $dependencies;
        $this->devDependencies = $devDependencies;
    }

    public function getPackageAlias(): string
    {
        return $this->packageAlias;
    }

    /**
     * @return array<ClassLike>
     */
    public function getAutoload(): array
    {
        return $this->autoload;
    }

    /**
     * @return array<ClassLike>
     */
    public function getDevAutoload(): array
    {
        return $this->devAutoload;
    }

    /**
     * @return array<ClassLike>
     */
    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    /**
     * @return array<ClassLike>
     */
    public function getDevDependencies(): array
    {
        return $this->devDependencies;
    }
}
