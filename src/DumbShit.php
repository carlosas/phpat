<?php

namespace PhpAT;

class DumbShit
{
    public const DUMB_SHIT = 'dumb shit';

    public function __construct()
    {
        $a = new DumbShit();
    }

    public function doSomething(string $asdf)
    {
        echo $asdf;
    }
}
