<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\fixtures\Dependency;

use Tests\PHPat\unit\fixtures\SimpleClass;

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
