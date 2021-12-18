<?php

namespace Tests\PhpAT\functional\php8\fixtures;

class TwiceDependingClass
{
    public function asdf()
    {
        $a = new SimpleClassOne();
        $b = new SimpleClassOne();
    }
}
