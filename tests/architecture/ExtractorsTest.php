<?php

use PhpAT\Rule\Type\Inheritance;
use PhpAT\Rule\Rule;
use PhpAT\Test\ArchitectureTest;

class CollectorsTest extends ArchitectureTest
{
    public function testCollectorsExtendAbstractCollector(): Rule
    {
        return $this->newRule
            ->filesLike('Parser/Collector/*Collector.php')
            ->excluding('Parser/Collector/AbstractCollector.php')
            ->shouldHave(Inheritance::class)
            ->withParams([
                'file' => 'Parser/AbstractCollector.php'
            ])
            ->build();
    }
}
