<?php

namespace Tests\PHPat\Architecture;

use PHPat\App\Cli\SingleCommandApplication;
use PHPat\Rule\Rule;
use PHPat\Selector\SelectorInterface;
use PHPat\Test\ArchitectureTest;

class OutputTest extends ArchitectureTest
{
    public function testOnlyEventSubscriberWritesOutput(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::haveClassName('*'))
            ->excludingClassesThat(SelectorInterface::havePath('*/Event/Listener/*Listener.php'))
            ->excludingClassesThat(SelectorInterface::haveClassName(SingleCommandApplication::class))
            ->excludingClassesThat(SelectorInterface::haveClassName('PHPat\App\Provider'))
            ->excludingClassesThat(SelectorInterface::havePath('App/EventListenerProvider.php'))
            ->excludingClassesThat(SelectorInterface::havePath('App.php'))
            ->mustNotDependOn()
            ->classesThat(SelectorInterface::haveClassName(\Symfony\Component\Console\Output\OutputInterface::class))
            ->build();
    }
}
