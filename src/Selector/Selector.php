<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPat\Selector\Modifier\AllOfSelectorModifier;
use PHPat\Selector\Modifier\AndModifier;
use PHPat\Selector\Modifier\AnyOfSelectorModifier;
use PHPat\Selector\Modifier\AtLeastCountOfSelectorModifier;
use PHPat\Selector\Modifier\AtMostCountOfSelectorModifier;
use PHPat\Selector\Modifier\NoneOfSelectorModifier;
use PHPat\Selector\Modifier\NotModifier;
use PHPat\Selector\Modifier\OneOfSelectorModifier;

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

    public static function AllOf(SelectorInterface ...$selectors): AllOfSelectorModifier
    {
        return new AllOfSelectorModifier(...$selectors);
    }

    public static function AnyOf(SelectorInterface ...$selectors): AnyOfSelectorModifier
    {
        return new AnyOfSelectorModifier(...$selectors);
    }

    public static function NoneOf(SelectorInterface ...$selectors): NoneOfSelectorModifier
    {
        return new NoneOfSelectorModifier(...$selectors);
    }

    public static function AtLeastCountOf(int $count, SelectorInterface ...$selectors): AtLeastCountOfSelectorModifier
    {
        return new AtLeastCountOfSelectorModifier($count, ...$selectors);
    }

    public static function AtMostCountOf(int $count, SelectorInterface ...$selectors): AtMostCountOfSelectorModifier
    {
        return new AtMostCountOfSelectorModifier($count, ...$selectors);
    }

    public static function OneOf(SelectorInterface ...$selectors): OneOfSelectorModifier
    {
        return new OneOfSelectorModifier(...$selectors);
    }
}
