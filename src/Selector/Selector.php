<?php

declare(strict_types=1);

namespace PhpAT\Selector;

class Selector
{
    public static function havePathname(string $pathname): PathnameSelector
    {
        return new PathnameSelector($pathname);
    }
}
