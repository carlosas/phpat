<?php

namespace PhpAT\Parser\Ast;

class ReferenceMap
{
    /**
     * @var SrcNode[]
     */
    private $srcNodes;
    /**
     * @var ClassLike[]
     */
    private $extensionNodes;
    /**
     * @var array
     */
    private $composerPackages;

    /**
     * ReferenceMap constructor.
     * @param SrcNode[]   $srcNodes
     * @param ClassLike[] $extensionNodes
     * @param array       $composerPackages
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
     * @return SrcNode[]
     */
    public function getSrcNodes(): array
    {
        return $this->srcNodes;
    }

    /**
     * @return ClassLike[]
     */
    public function getExtensionNodes(): array
    {
        return $this->extensionNodes;
    }

    /**
     * @return array
     */
    public function getComposerPackages(): array
    {
        return $this->composerPackages;
    }
}
