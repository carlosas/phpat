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
            ->classesThat(Selector::areAutoloadableFromComposer())
            ->canOnlyDependOn()
            ->classesThat(Selector::areAutoloadableFromComposer())
            ->classesThat(Selector::areDependenciesFromComposer())
            ->build();
    }

    public function testAssertionsDoNotDependOnVendors(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::haveClassName('PhpAT\Rule\Assertion\*'))
            ->mustNotDependOn()
            ->classesThat(Selector::areDependenciesFromComposer('main'))
            ->excludingClassesThat(Selector::haveClassName('PHPAT\*'))
            ->build();
    }
}
