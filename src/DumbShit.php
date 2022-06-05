<?php

declare(strict_types=1);

namespace PHPat;

class DumbShit extends SomeAbstractClass
{
    public const DUMB_SHIT = 'dumb shit';

    public function __construct()
    {
        $a = new DumbShit();
    }

    public function doSomething(string $asdf): void
    {
        echo $asdf;
    }
}
