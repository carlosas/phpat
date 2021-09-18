<?php

namespace PhpAT\App\Helper;

class PathNormalizer
{
    public static function normalizePathname(string $pathname): string
    {
        return str_replace('\\', '/', realpath($pathname));
    }
}
