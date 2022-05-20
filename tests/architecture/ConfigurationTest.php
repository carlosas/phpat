<?php

namespace Tests\PHPat\architecture;

use PHPat\Configuration;
use PHPat\Selector\Selector;
use PHPat\Test\Rule;
use PHPat\Test\PHPat;

class ConfigurationTest
{
    public function test_configuration_does_not_depend_on_rules(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::classname(Configuration::class))
            ->mustNotDependOn()
            ->classes(Selector::namespace('PHPat\\'))
            ->build();
    }
}
