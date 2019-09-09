<?php

namespace PhpAT\Parser;

use PhpParser\NodeVisitorAbstract;

class AbstractExtractor extends NodeVisitorAbstract
{
    /**
     * @var array
     */
    protected $result = [];

    public function getResult(): array
    {
        return $this->result;
    }
}
