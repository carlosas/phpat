<?php

namespace PhpAT\Parser\Ast\Traverser;

use PhpAT\Parser\Ast\FullClassName;

class TraverseContext
{
    private static $pathname;
    private static $class;

    public static function startFile(string $pathname)
    {
        self::$pathname = $pathname;
        self::$class = null;
    }

    public static function registerClass(FullClassName $className)
    {
        self::$class = $className;
    }


    public static function pathname(): string
    {
        return self::$pathname;
    }

    public static function className()
    {
        return self::$class;
    }
}
