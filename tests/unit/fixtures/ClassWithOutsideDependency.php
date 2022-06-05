<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\fixtures;

use Tests\PHPat\unit\FixtureOutOfPathOne;
use Tests\PHPat\unit\FixtureOutOfPathTwo;

class ClassWithOutsideDependency
{
    public function methodOne(FixtureOutOfPathOne $fixture): void
    {
    }

    public function methodTwo(FixtureOutOfPathTwo $fixture): void
    {
    }
}
