<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPat\Selector\Modifier\AndModifier;
use PHPat\Selector\Modifier\NotModifier;

final class Selector extends SelectorPrimitive
{
    public static function AND(SelectorInterface ...$selector): AndModifier
    {
        return new AndModifier(...$selector);
    }

    public static function NOT(SelectorInterface $selector): NotModifier
    {
        return new NotModifier($selector);
    }
}
