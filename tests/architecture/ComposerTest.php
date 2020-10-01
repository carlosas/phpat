<?php

namespace Tests\PhpAT\architecture;

use PhpAT\Rule\Rule;
use PhpAT\Selector\Selector;
use PhpAT\Test\ArchitectureTest;

class ComposerTest extends ArchitectureTest
{
    //TODO: ComposerFileParser does not parse 'files' from autoload
    /*public function testOnlyDependsOnComposerDependencies(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::areAutoloadableFromComposer('main'))
            ->canOnlyDependOn()
            ->classesThat(Selector::areAutoloadableFromComposer('main'))
            ->classesThat(Selector::areDependenciesFromComposer('main'))
            ->build();
    }*/

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
