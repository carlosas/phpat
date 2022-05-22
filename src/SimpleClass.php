<?php

namespace PHPat;

class SimpleClass
{
    public function asdf(DumbShit $dumb): DumbShitTwo
    {
        $dumb->doSomething(DumbShit::DUMB_SHIT);

        return new DumbShitTwo();
    }
}
