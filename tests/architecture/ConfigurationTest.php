<?php declare(strict_types=1);

namespace Tests\PHPat\architecture;

use PHPat\Configuration;
use PHPat\Selector\Selector;
use PHPat\Test\Attributes\TestRule;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;

final class ConfigurationTest
{
    #[TestRule]
    public function configuration_does_not_have_dependencies(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::classname(Configuration::class))
            ->shouldNotDependOn()
            ->classes(Selector::all())
        ;
    }

    #[TestRule]
    public function configuration_is_final(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::classname(Configuration::class))
            ->shouldBeFinal()
        ;
    }
}
