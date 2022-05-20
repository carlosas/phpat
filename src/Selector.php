<?php

declare(strict_types=1);

namespace PhpAT;

use PhpAT\Selector\ClassExtends;
use PhpAT\Selector\ClassImplements;
use PhpAT\Selector\Classname;

class Selector
{
    public static function classname(string $fqcn): Classname
    {
        return new Classname($fqcn);
    }

    public static function implements(string $fqcn): ClassImplements
    {
        return new ClassImplements($fqcn);
    }
    public static function extends(string $fqcn): ClassExtends
    {
        return new ClassExtends($fqcn);
    }
}
