<?php

namespace PhpAT\Parser\Ast\Classmap;

use PhpAT\Parser\Ast\FullClassName;

final class ClassmapRelation
{
    /** @var FullClassName */
    public $relatedClass;
    /** @var int */
    public $startLine;
    /** @var int */
    public $endLine;

    public function __construct(FullClassName $relatedClass, int $startLine, int $endLine)
    {
        $this->relatedClass = $relatedClass;
        $this->startLine = $startLine;
        $this->endLine = $endLine;
    }
}
