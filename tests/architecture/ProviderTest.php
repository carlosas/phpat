<?php

use PhpAT\Rule\Type\Dependency;
use PhpAT\Rule\Rule;
use PhpAT\Test\ArchitectureTest;

class ProviderTest extends ArchitectureTest
{
    public function testProviderDependsOnApp(): Rule
    {
        return $this->newRule
            ->filesLike('DependencyInjection/Provider.php')
            ->shouldHave(Dependency::class)
            ->withParams([
                'files' => ['App.php']
                //TODO: Fix get new instances as dependencies
                //'files' => ['DependencyInjection/Configuration.php']
            ])
            ->build();
    }

    public function testAppDoesNotDependOnProvider(): Rule
    {
        return $this->newRule
            ->filesLike('App.php')
            ->shouldNotHave(Dependency::class)
            ->withParams([
                'files' => ['DependencyInjection/Provider.php']
            ])
            ->build();
    }
}
