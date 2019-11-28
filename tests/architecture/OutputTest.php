<?php

use PhpAT\Selector\Selector;
use PhpAT\Rule\Rule;
use PhpAT\Test\ArchitectureTest;

class OutputTest extends ArchitectureTest
{
    public function testOnlyEventSubscriberWritesOutput(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePath('*'))
            ->excludingClassesThat(Selector::havePath('App/EventSubscriber.php'))
            ->excludingClassesThat(Selector::havePath('App/Event.php'))
            ->excludingClassesThat(Selector::havePath('App/Provider.php'))
            ->excludingClassesThat(Selector::havePath('App.php'))
            ->excludingClassesThat(Selector::havePath('Output/StdOutput.php'))
            ->mustNotDependOn()
            ->classesThat(Selector::havePath('Output/OutputInterface.php'))
            ->build();
    }
}
