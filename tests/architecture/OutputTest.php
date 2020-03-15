<?php

use PhpAT\Selector\Selector;
use PhpAT\Rule\Rule;
use PhpAT\Test\ArchitectureTest;

final class OutputTest extends ArchitectureTest
{
    public function testOnlyEventSubscriberWritesOutput(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::haveClassName('*'))
            ->excludingClassesThat(Selector::havePath('*/Event/Listener/*Listener.php'))
            ->excludingClassesThat(Selector::haveClassName('PhpAT\App\Provider'))
            ->excludingClassesThat(Selector::havePath('App/ListenerProvider.php'))
            ->excludingClassesThat(Selector::havePath('App.php'))
            ->excludingClassesThat(Selector::havePath('Output/StdOutput.php'))
            ->mustNotDependOn()
            ->classesThat(Selector::havePath('Output/OutputInterface.php'))
            ->build();
    }
}
