<?php

namespace Tests\PhpAT\functional\php7\fixtures;

use Tests\PhpAT\functional\FixtureOutOfPathOne;
use Tests\PhpAT\functional\FixtureOutOfPathTwo;

class ClassWithOutsideDependency
{
    public function methodOne(FixtureOutOfPathOne $fixture)
    {
        return;
    }

    public function methodTwo(FixtureOutOfPathTwo $fixture)
    {
        return;
    }
}
