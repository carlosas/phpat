<?php

use PhpAT\Selector\Selector;
use PhpAT\Rule\Rule;
use PhpAT\Test\ArchitectureTest;

class OutputTest extends ArchitectureTest
{
    public function testOnlyEventSubscriberWritesOutput(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePathname('*'))
            ->excludingClassesThat(Selector::havePathname('App/EventSubscriber.php'))
            ->excludingClassesThat(Selector::havePathname('App/Provider.php'))
            ->excludingClassesThat(Selector::havePathname('App.php'))
            ->shouldNotDependOn()
            ->classesThat(Selector::havePathname('Output/OutputInterface.php'))
            ->build();
    }
}
