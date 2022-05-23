<?php

namespace Tests\PHPat\architecture;

use PHPat\DumbShit;
use PHPat\Selector\Selector;
use PHPat\SimpleClass;
use PHPat\SomeAbstractClass;
use PHPat\SomeInterface;
use PHPat\Test\Rule;
use PHPat\Test\PHPat;

class FailingTest
{
    public function test_simple_does_not_depend_on_dumbshits(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::classname(SimpleClass::class))
            ->mustNotDependOn()
            ->classes(
                Selector::classname(DumbShit::class),
                Selector::implements(SomeInterface::class)
            )
            ->build();
    }

    public function test_simple_does_not_construct_something(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::classname(SimpleClass::class))
            ->mustNotConstruct()
            ->classes(Selector::implements(SomeInterface::class))
            ->build();
    }

    public function test_dumbshit_does_not_extends_abstract(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::classname(DumbShit::class))
            ->mustNotExtend()
            ->classes(Selector::classname(SomeAbstractClass::class))
            ->build();
    }
}
