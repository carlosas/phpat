<?php

namespace Tests\PHPat\Architecture;

use PHPat\Rule\Rule;
use PHPat\Selector\SelectorInterface;
use PHPat\Test\ArchitectureTest;

class ComposerTest extends ArchitectureTest
{
    public function testOnlyDependsOnComposerDependencies(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::areAutoloadableFromComposer())
            ->canOnlyDependOn()
            ->classesThat(SelectorInterface::areAutoloadableFromComposer())
            ->classesThat(SelectorInterface::areDependenciesFromComposer())
            ->build();
    }

    public function testAssertionsDoNotDependOnVendors(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::haveClassName('PHPat\Rule\Assertion\*'))
            ->mustNotDependOn()
            ->classesThat(SelectorInterface::areDependenciesFromComposer())
            ->excludingClassesThat(SelectorInterface::haveClassName('PHPAT\*'))
            ->andExcludingClassesThat(SelectorInterface::haveClassName('Psr\*'))
            ->build();
    }
}
