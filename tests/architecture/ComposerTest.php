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
            ->classesThat(Selector::areAutoloadableFromComposer(__DIR__ . '/../../composer.json'))
            ->classesThat(Selector::areDependenciesFromComposer(__DIR__ . '/../../composer.json', __DIR__ . '/../../composer.lock'))
            ->build();
    }

    public function testAssertionsDoNotDependOnVendors(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::haveClassName('PhpAT\Rule\Assertion\*'))
            ->mustNotDependOn()
            ->classesThat(Selector::areDependenciesFromComposer(__DIR__ . '/../../composer.json', __DIR__ . '/../../composer.lock'))
            ->excludingClassesThat(Selector::haveClassName('PHPAT\*'))
            ->andExcludingClassesThat(Selector::haveClassName('Psr\*'))
            ->build();
    }
}
