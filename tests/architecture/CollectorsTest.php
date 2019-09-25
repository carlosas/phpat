<?php

use PhpAT\Rule\Selector;
use PhpAT\Rule\Rule;
use PhpAT\Test\ArchitectureTest;

class CollectorsTest extends ArchitectureTest
{
    public function testCollectorsExtendAbstractCollector(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePathname('Parser/Collector/*Collector.php'))
            ->excludingClassesThat(Selector::havePathname('Parser/Collector/AbstractCollector.php'))
            ->shouldExtend()
            ->classesThat(Selector::havePathname('Parser/AbstractCollector.php'))
            ->build();
    }
}
