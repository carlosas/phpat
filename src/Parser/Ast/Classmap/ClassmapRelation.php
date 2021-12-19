<?php

namespace PhpAT\Parser\Ast\Classmap;

use PhpAT\Parser\Ast\FullClassName;

final class ClassmapRelation
{
    public FullClassName $relatedClass;
    public int $startLine;
    public int $endLine;

    public function __construct(FullClassName $relatedClass, int $startLine, int $endLine)
    {
        $this->relatedClass = $relatedClass;
        $this->startLine = $startLine;
        $this->endLine = $endLine;
    }
}
