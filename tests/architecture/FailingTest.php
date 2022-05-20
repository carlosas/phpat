<?php

namespace Tests\PhpAT\architecture;

use PhpAT\DumbShit;
use PhpAT\Selector\Selector;
use PhpAT\SimpleClass;
use PhpAT\SomeInterface;
use PhpAT\Test\Rule;
use PhpAT\Test\Phpat;

class FailingTest
{
    public function test_configuration_does_not_depend_on_rules(): Rule
    {
        return Phpat::rule()
            ->classes(Selector::classname(SimpleClass::class))
            ->mustNotDependOn()
            ->classes(
                Selector::classname(DumbShit::class),
                Selector::implements(SomeInterface::class)
            )
            ->build();
    }
}
