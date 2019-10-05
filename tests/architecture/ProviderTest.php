<?php

use PhpAT\Selector\Selector;
use PhpAT\Rule\Rule;
use PhpAT\Test\ArchitectureTest;

class ProviderTest extends ArchitectureTest
{
    public function testProviderDependsOnApp(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePathname('DependencyInjection/Provider.php'))
            ->shouldDependOn()
            ->classesThat(Selector::havePathname('App.php'))
            ->build();
    }

    public function testAppDoesNotDependOnProvider(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePathname('App.php'))
            ->shouldNotDependOn()
            ->classesThat(Selector::havePathname('DependencyInjection/Provider.php'))
            ->build();
    }
}
