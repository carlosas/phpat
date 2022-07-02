<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\fixtures\Dependency;

use Tests\PHPat\unit\fixtures\SimpleClass;

class Others
{
    public function shouldNotBeCatched(SimpleClass $class, int $number): \int
    {
        $b = (string) is_null($class);
        $c = (bool) \is_null($class);

        return $number;
    }
}
