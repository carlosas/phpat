<?php

namespace PhpAT\Parser\Ast;

class ReferenceMap
{
    /** @var array<SrcNode> */
    private array $srcNodes;
    /** @var array<ClassLike> */
    private array $extensionNodes;
    /** @var array<string, ComposerPackage> */
    private array $composerPackages;

    /**
     * ReferenceMap constructor.
     * @param array<SrcNode>   $srcNodes
     * @param array<ClassLike> $extensionNodes
     * @param array<string, ComposerPackage> $composerPackages
     */
    public function __construct(
        array $srcNodes,
        array $extensionNodes,
        array $composerPackages
    ) {
        $this->srcNodes = $srcNodes;
        $this->extensionNodes = $extensionNodes;
        $this->composerPackages = $composerPackages;
    }

    /**
     * @return array<SrcNode>
     */
    public function getSrcNodes(): array
    {
        return $this->srcNodes;
    }

    /**
     * @return array<ClassLike>
     */
    public function getExtensionNodes(): array
    {
        return $this->extensionNodes;
    }

    /**
     * @return array<string, ComposerPackage>
     */
    public function getComposerPackages(): array
    {
        return $this->composerPackages;
    }
}
