<?php

declare(strict_types=1);

namespace PhpAT\Selector;

class Selector
{
    public static function havePathname(string $pathname): HavePathname
    {
        return new HavePathname($pathname);
    }
}
