<?php

namespace Tests\PHPat\unit\php7\fixtures;

use Tests\PHPat\unit\FixtureOutOfPathOne;
use Tests\PHPat\unit\FixtureOutOfPathTwo;

class ClassWithOutsideDependency
{
    public function methodOne(FixtureOutOfPathOne $fixture)
    {
    }

    public function methodTwo(FixtureOutOfPathTwo $fixture)
    {
    }
}
