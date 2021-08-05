<?php

namespace Tests\PhpAT\functional\php7\fixtures\Dependency;

use Tests\PhpAT\functional\php7\fixtures\SimpleClass;

class Others
{
    public function shouldNotBeCatched(SimpleClass $class, int $number): \int
    {
        $b = (string) is_null($class);
        $c = (bool) \is_null($class);

        return $number;
    }
}
