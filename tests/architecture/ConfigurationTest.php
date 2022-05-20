<?php

namespace Tests\PhpAT\architecture;

use PhpAT\Configuration;
use PhpAT\Selector\Selector;
use PhpAT\Test\Rule;
use PhpAT\Test\Phpat;

class ConfigurationTest
{
    public function test_configuration_does_not_depend_on_rules(): Rule
    {
        return Phpat::rule()
            ->classes(Selector::classname(Configuration::class))
            ->mustNotDependOn()
            ->classes(Selector::classname(Rule::class)) //change to all namespace
            ->build();
    }
}
