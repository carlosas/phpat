<?php declare(strict_types=1);

namespace PhpAT\Rule;

class Selector
{
    public static function havePathname(string $pathname): string
    {
        return $pathname;
    }
}
