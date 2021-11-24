<?php

namespace PhpAT\Parser\Relation;

use PhpAT\Parser\Ast\FullClassName;

class AbstractRelation
{
    public \PhpAT\Parser\Ast\FullClassName $relatedClass;
    public int $line;

    public function __construct(int $line, FullClassName $relatedClass)
    {
        $this->line = $line;
        $this->relatedClass = $relatedClass;
    }
}
