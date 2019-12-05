<?php

namespace PhpAT\Parser\Collector;

use PhpParser\NodeVisitorAbstract;

class AbstractCollector extends NodeVisitorAbstract
{
    /**
     * @var array
     */
    protected $result = [];

    public function beforeTraverse(array $nodes) {
        $this->result = [];
        return null;
    }

    public function getResult(): array
    {
        return $this->result;
    }
}
