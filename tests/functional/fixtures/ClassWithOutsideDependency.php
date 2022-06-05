<?php

namespace Tests\PHPat\functional\fixtures;

use Tests\PHPat\functional\FixtureOutOfPathOne;
use Tests\PHPat\functional\FixtureOutOfPathTwo;

class ClassWithOutsideDependency
{
    public function methodOne(FixtureOutOfPathOne $fixture)
    {

    }

    public function methodTwo(FixtureOutOfPathTwo $fixture)
    {

    }
}
