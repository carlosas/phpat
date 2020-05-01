<?php

namespace PhpAT\Parser\Ast;

class ReferenceMap
{
    /**
     * @var SrcNode[]
     */
    private $srcNodes;

    /**
     * ReferenceMap constructor.
     * @param SrcNode[] $srcNodes
     */
    public function __construct(
        array $srcNodes
    ) {
        $this->srcNodes = $srcNodes;
    }

    /**
     * @return SrcNode[]
     */
    public function getSrcNodes(): array
    {
        return $this->srcNodes;
    }
}
