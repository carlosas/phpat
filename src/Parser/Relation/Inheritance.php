<?php

namespace PhpAT\Parser\Relation;

use PhpAT\Parser\FullClassName;

class Inheritance extends AbstractRelation
{
    public function __construct(int $line, FullClassName $relatedClass)
    {
        parent::__construct($line, $relatedClass);
    }
}
