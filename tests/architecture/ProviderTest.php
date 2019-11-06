<?php

use PhpAT\Selector\Selector;
use PhpAT\Rule\Rule;
use PhpAT\Test\ArchitectureTest;

class ProviderTest extends ArchitectureTest
{
    public function testProviderDependsOnApp(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePath('App/Provider.php'))
            ->mustDependOn()
            ->classesThat(Selector::havePath('App.php'))
            ->build();
    }

    public function testAppDoesNotDependOnProvider(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePath('App.php'))
            ->mustNotDependOn()
            ->classesThat(Selector::havePath('App/Provider.php'))
            ->build();
    }
}
