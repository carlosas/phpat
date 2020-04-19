<?php

namespace PhpAT\Parser\Ast;

class ReferenceMap
{
    /**
     * @var AstNode[]
     */
    private $srcNodes;
    /**
     * @var ComposerModule[]
     */
    private $composerMap;

    /**
     * ReferenceMap constructor.
     * @param AstNode[] $srcNodes
     * @param ComposerModule[] $composerMap
     */
    public function __construct(
        array $srcNodes,
        array $composerMap
    ) {
        $this->srcNodes = $srcNodes;
        $this->composerMap = $composerMap;
    }

    /**
     * @return AstNode[]
     */
    public function getSrcNodes(): array
    {
        return $this->srcNodes;
    }

    /**
     * @return ComposerModule[]
     */
    public function getComposerMap(): array
    {
        return $this->composerMap;
    }
}
