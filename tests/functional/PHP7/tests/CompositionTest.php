<?php

namespace Tests\PhpAT\functional\PHP7\tests;

use PhpAT\Rule\Rule;
use PhpAT\Selector\Selector;
use PhpAT\Test\ArchitectureTest;

class CompositionTest extends ArchitectureTest
{
    public function testSimpleInterfaceComposition(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePathname('Composition/Composed.php'))
            ->shouldImplement()
            ->classesThat(Selector::havePathname('SimpleInterface.php'))
            ->build();
    }

    public function testMultipleInterfaceComposition(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePathname('Composition/MultipleComposed.php'))
            ->shouldImplement()
            ->classesThat(Selector::havePathname('SimpleInterface.php'))
            ->andClassesThat(Selector::havePathname('Composition/CompositionNamespaceSimpleInterface.php'))
            ->build();
    }
}
