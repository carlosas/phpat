<?php

declare(strict_types=1);

namespace Tests\PHPat\functional\fixtures\Dependency;

use Tests\PHPat\functional\fixtures\SimpleClass;

class SelfStatic
{
    public function doSomething(): self
    {
        return $this;
    }

    public function shouldNotBeCatched(): self
    {
        $a = new SimpleClass();

        return static::doSomething();
    }
}
