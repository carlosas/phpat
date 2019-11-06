<?php

declare(strict_types=1);

namespace PhpAT\Selector;

class Selector
{
    public static function havePath(string $path): PathSelector
    {
        return new PathSelector($path);
    }
}
