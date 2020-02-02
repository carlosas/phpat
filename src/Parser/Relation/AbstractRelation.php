<?php

namespace PhpAT\Parser\Relation;

use PhpAT\Parser\ClassName;

class AbstractRelation
{
    /** @var ClassName */
    public $relatedClass;
    /** @var int */
    public $line;

    public function __construct(int $line, ClassName $relatedClass)
    {
        $this->line = $line;
        $this->relatedClass = $relatedClass;
    }
}
