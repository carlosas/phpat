<?php

use PhpAT\Selector\Selector;
use PhpAT\Rule\Rule;
use PhpAT\Test\ArchitectureTest;

class CollectorsTest extends ArchitectureTest
{
    public function testCollectorsExtendAbstractCollector(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePath('Parser/Collector/*Collector.php'))
            ->excludingClassesThat(Selector::havePath('Parser/Collector/AbstractCollector.php'))
            ->mustExtend()
            ->classesThat(Selector::havePath('Parser/AbstractCollector.php'))
            ->build();
    }
}
