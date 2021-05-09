<?php

use PhpAT\Selector\Selector;
use PhpAT\Rule\Rule;
use PhpAT\Test\ArchitectureTest;

class OutputTest extends ArchitectureTest
{
    public function testOnlyEventSubscriberWritesOutput(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::haveClassName('*'))
            ->excludingClassesThat(Selector::havePath('*/Event/Listener/*Listener.php'))
            ->excludingClassesThat(Selector::haveClassName(\PhpAT\App\Cli\SingleCommandApplication::class))
            ->excludingClassesThat(Selector::haveClassName('PhpAT\App\Provider'))
            ->excludingClassesThat(Selector::havePath('App/EventListenerProvider.php'))
            ->excludingClassesThat(Selector::havePath('App.php'))
            ->mustNotDependOn()
            ->classesThat(Selector::haveClassName(\Symfony\Component\Console\Output\OutputInterface::class))
            ->build();
    }
}
