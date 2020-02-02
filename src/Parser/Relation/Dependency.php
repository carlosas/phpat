<?php

namespace PhpAT\Parser\Relation;

use PhpAT\Parser\ClassName;

class Dependency extends AbstractRelation
{
    public function __construct(int $line, ClassName $relatedClass)
    {
        parent::__construct($line, $relatedClass);
    }
}
