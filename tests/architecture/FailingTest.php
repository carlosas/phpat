<?php

declare(strict_types=1);

namespace Tests\PHPat\architecture;

use PHPat\DumbShit;
use PHPat\DumbShitTwo;
use PHPat\Selector\Selector;
use PHPat\SimpleClass;
use PHPat\SomeAbstractClass;
use PHPat\SomeInterface;
use PHPat\Test\PHPat;
use PHPat\Test\Rule;

class FailingTest
{
    public function test_dumbshit_implements_interface(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::classname(DumbShit::class))
            ->shouldImplement()
            ->classes(Selector::classname(SomeInterface::class))
            ->build();
    }

    public function test_simple_does_not_depend_on_dumbshits(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::classname(SimpleClass::class))
            ->shouldNotDependOn()
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
            ->shouldNotConstruct()
            ->classes(Selector::implements(SomeInterface::class))
            ->build();
    }

    public function test_dumbshit_does_not_extends_abstract(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::classname(DumbShit::class))
            ->shouldNotExtend()
            ->classes(Selector::classname(SomeAbstractClass::class))
            ->build();
    }

    public function test_dumbshittwo_does_not_implement_interface(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::classname(DumbShitTwo::class))
            ->shouldNotImplement()
            ->classes(Selector::classname(SomeInterface::class))
            ->build();
    }
}
