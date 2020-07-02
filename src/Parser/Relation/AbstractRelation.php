<?php

namespace PhpAT\Parser\Relation;

use PhpAT\Parser\Ast\FullClassName;

class AbstractRelation
{
    /** @var FullClassName */
    public $relatedClass;
    /** @var int */
    public $line;

    public function __construct(int $line, FullClassName $relatedClass)
    {
        $this->line = $line;
        $this->relatedClass = $relatedClass;
    }
}
