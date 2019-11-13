<?php declare(strict_types=1);

namespace PhpAT\Parser\Collector;

use PhpParser\NodeVisitorAbstract;

class AbstractCollector extends NodeVisitorAbstract
{
    /** @var array */
    protected $result = [];

    public function getResult(): array
    {
        return $this->result;
    }
}
