<?php

namespace Tests\PHPat\functional\fixtures;

use Tests\PHPat\functional\FixtureOutOfPathOne;
use Tests\PHPat\functional\FixtureOutOfPathTwo;

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
