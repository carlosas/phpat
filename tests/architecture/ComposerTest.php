<?php


namespace Tests\PhpAT\architecture;


use PhpAT\Rule\Rule;
use PhpAT\Selector\Selector;
use PhpAT\Test\ArchitectureTest;

class ComposerTest extends ArchitectureTest
{

    public function testOnlyDependsOnComposerDependencies(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::areComposerSource(__DIR__ . '/../../composer.json'))
            ->canOnlyDependOn()
            ->classesThat(Selector::areComposerDependencies(__DIR__ . '/../../composer.json', __DIR__ . '/../../composer.lock'))
            ->build();
    }

}