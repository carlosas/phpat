<?php

declare(strict_types=1);

namespace Tests\PHPat\architecture;

use PHPat\Configuration;
use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;

class ConfigurationTest
{
    public function test_configuration_does_not_have_dependencies(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::classname(Configuration::class))
            ->shouldNotDependOn()
            ->classes(Selector::all());
    }

    public function test_configuration_is_final(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::classname(Configuration::class))
            ->shouldBeFinal();
    }

    public function test_configuration_is_immutable(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::classname(Configuration::class))
            ->shouldBeImmutable();
    }
}
