<?php

namespace Tests\PHPat\architecture;

use PHPat\DumbShit;
use PHPat\Selector\Selector;
use PHPat\SimpleClass;
use PHPat\SomeInterface;
use PHPat\Test\Rule;
use PHPat\Test\PHPat;

class FailingTest
{
    public function test_configuration_does_not_depend_on_rules(): Rule
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
}
