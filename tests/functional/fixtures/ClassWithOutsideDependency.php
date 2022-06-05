<?php

declare(strict_types=1);

namespace Tests\PHPat\functional\fixtures;

use Tests\PHPat\functional\FixtureOutOfPathOne;
use Tests\PHPat\functional\FixtureOutOfPathTwo;

class ClassWithOutsideDependency
{
    public function methodOne(FixtureOutOfPathOne $fixture): void
    {
    }

    public function methodTwo(FixtureOutOfPathTwo $fixture): void
    {
    }
}
