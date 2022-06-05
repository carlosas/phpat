<?php

declare(strict_types=1);

namespace PHPat;

class SimpleClass
{
    private DumbShitTwo $dumbShitTwo;

    public function asdf(DumbShit $dumb): DumbShitTwo
    {
        $dumb->doSomething(DumbShit::DUMB_SHIT);
        $this->dumbShitTwo = new DumbShitTwo();

        return $this->dumbShitTwo;
    }
}
