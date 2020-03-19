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
            ->classesThat(Selector::areAutoloadableFromComposer(__DIR__ . '/../../composer.json'))
            ->canOnlyDependOn()
            ->classesThat(Selector::areDependenciesFromComposer(__DIR__ . '/../../composer.json', __DIR__ . '/../../composer.lock'))
            ->build();
    }
}
