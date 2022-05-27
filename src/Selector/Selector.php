<?php

declare(strict_types=1);

namespace PHPat\Selector;

use PHPat\Selector\Modifier\Not;

class Selector extends SelectorPrimitive
{
    public static function NOT(SelectorInterface $selector): Not
    {
        return new Not($selector);
    }
}
