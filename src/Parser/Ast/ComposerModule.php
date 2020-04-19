<?php

namespace PhpAT\Parser\Ast;

use PhpAT\Parser\ClassLike;
use PhpAT\Parser\FullClassName;
use PhpAT\Parser\RegexClassName;
use PhpAT\Parser\Relation\AbstractRelation;

class ComposerModule
{
    /**
     * @var ClassLike[]
     */
    private $mainAutoloadNamespaces;
    /**
     * @var ClassLike[]
     */
    private $devAutoloadNamespaces;
    /**
     * @var array
     */
    private $mainDirectRequirements;
    /**
     * @var array
     */
    private $devDirectRequirements;
    /**
     * @var array
     */
    private $allPackagesNamespaces;
    /**
     * @var array
     */
    private $deepDependencies;

    /**
     * ComposerModule constructor.
     * @param ClassLike[] $mainAutoloadNamespaces
     * @param ClassLike[] $devAutoloadNamespaces
     * @param array       $mainDirectRequirements
     * @param array       $devDirectRequirements
     * @param array       $allPackagesNamespaces
     * @param array       $deepDependencies
     */
    public function __construct(
        array $mainAutoloadNamespaces,
        array $devAutoloadNamespaces,
        array $mainDirectRequirements,
        array $devDirectRequirements,
        array $allPackagesNamespaces,
        array $deepDependencies
    ) {
        $this->mainAutoloadNamespaces = $mainAutoloadNamespaces;
        $this->devAutoloadNamespaces = $devAutoloadNamespaces;
        $this->mainDirectRequirements = $mainDirectRequirements;
        $this->devDirectRequirements = $devDirectRequirements;
        $this->allPackagesNamespaces = $allPackagesNamespaces;
        $this->deepDependencies = $deepDependencies;
    }

    /**
     * @return ClassLike[]
     */
    public function getMainAutoloadNamespaces(): array
    {
        return $this->mainAutoloadNamespaces;
    }

    /**
     * @return ClassLike[]
     */
    public function getDevAutoloadNamespaces(): array
    {
        return $this->devAutoloadNamespaces;
    }

    /**
     * @return array
     */
    public function getMainDirectRequirements(): array
    {
        return $this->mainDirectRequirements;
    }

    /**
     * @return array
     */
    public function getDevDirectRequirements(): array
    {
        return $this->devDirectRequirements;
    }

    /**
     * @return array
     */
    public function getAllPackagesNamespaces(): array
    {
        return $this->allPackagesNamespaces;
    }

    /**
     * @return array
     */
    public function getDeepDependencies(): array
    {
        return $this->deepDependencies;
    }
}
