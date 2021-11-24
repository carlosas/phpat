<?php

namespace PhpAT\Parser\Ast;

class ComposerPackage
{
    private string $packageAlias;
    /** ClassLike[] */
    private $autoload;
    /** ClassLike[] */
    private $devAutoload;
    /** ClassLike[] */
    private $dependencies;
    /** ClassLike[] */
    private $devDependencies;

    /**
     * ComposerPackage constructor.
     * @param ClassLike[] $autoload
     * @param ClassLike[] $devAutoload
     * @param ClassLike[] $dependencies
     * @param ClassLike[] $devDependencies
     */
    public function __construct(
        string $packageAlias,
        array $autoload,
        array $devAutoload,
        array $dependencies,
        array $devDependencies
    ) {
        $this->packageAlias = $packageAlias;
        $this->autoload = $autoload;
        $this->devAutoload = $devAutoload;
        $this->dependencies = $dependencies;
        $this->devDependencies = $devDependencies;
    }

    public function getPackageAlias(): string
    {
        return $this->packageAlias;
    }

    /**
     * @return ClassLike[]
     */
    public function getAutoload(): array
    {
        return $this->autoload;
    }

    /**
     * @return ClassLike[]
     */
    public function getDevAutoload(): array
    {
        return $this->devAutoload;
    }

    /**
     * @return ClassLike[]
     */
    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    /**
     * @return ClassLike[]
     */
    public function getDevDependencies(): array
    {
        return $this->devDependencies;
    }
}
