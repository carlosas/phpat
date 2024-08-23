<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPat\Selector\Modifier\AndModifier;
use PHPat\Selector\Modifier\NotModifier;
use PHPat\Selector\Modifier\OrModifier;

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

    public static function OR(SelectorInterface ...$selector): OrModifier
    {
        return new OrModifier(...$selector);
    }
}
