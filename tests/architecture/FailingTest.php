<?php

namespace Tests\PhpAT\architecture;

use PhpAT\ArchitectureTest;
use PhpAT\DumbShit;
use PhpAT\Selector\Selector;
use PhpAT\SimpleClass;
use PhpAT\SomeInterface;
use PhpAT\Test\Rule;

class FailingTest extends ArchitectureTest
{
    public function test_configuration_does_not_depend_on_rules(): Rule
    {
        return $this->rule()
            ->classes(Selector::classname(SimpleClass::class))
            ->mustNotDependOn()
            ->classes(
                Selector::classname(DumbShit::class),
                Selector::implements(SomeInterface::class)
            )
            ->build();
    }
}
