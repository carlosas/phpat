<?php

namespace PhpAT\Parser;

class Dependency
{
    /**
     * @var string
     */
    private $pathname;

    public function __construct(string $pathname)
    {
        $this->pathname = $pathname;
    }

    /**
     * @return string
     */
    public function getPathname(): string
    {
        return $this->pathname;
    }
}
