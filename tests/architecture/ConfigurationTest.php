<?php

namespace Tests\PHPat\architecture;

use PHPat\Configuration;
use PHPat\Selector\Selector;
use PHPat\Test\PHPat;
use PHPat\Test\Rule;

class ConfigurationTest
{
    public function test_configuration_does_not_depend_on_rules(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::classname(Configuration::class))
            ->shouldNotDependOn()
            ->classes(Selector::namespace('PHPat'))
            ->build();
    }
}
