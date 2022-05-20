<?php

namespace Tests\PhpAT\architecture;

use PhpAT\ArchitectureTest;
use PhpAT\Configuration;
use PhpAT\Selector\Selector;
use PhpAT\Test\Rule;

class ConfigurationTest extends ArchitectureTest
{
    public function test_configuration_does_not_depend_on_rules(): Rule
    {
        return $this->rule()
            ->classes(Selector::classname(Configuration::class))
            ->mustNotDependOn()
            ->classes(Selector::classname(Rule::class)) //change to all namespace
            ->build();
    }
}
