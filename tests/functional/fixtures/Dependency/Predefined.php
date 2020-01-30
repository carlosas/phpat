<?php

namespace Tests\PhpAT\functional\fixtures\Dependency;

use Tests\PhpAT\functional\fixtures\SimpleClass;

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
