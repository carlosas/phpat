<?php

namespace PhpAT\Parser\Ast;

use PhpAT\Parser\ClassLike;

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
     * ReferenceMap constructor.
     * @param SrcNode[]   $srcNodes
     * @param ClassLike[] $extensionNodes
     */
    public function __construct(
        array $srcNodes,
        array $extensionNodes
    ) {
        $this->srcNodes = $srcNodes;
        $this->extensionNodes = $extensionNodes;
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
}
