<?php

namespace Tests\PhpAT\functional\php7\architecture;

use PhpAT\Rule\Rule;
use PhpAT\Selector\Selector;
use PhpAT\Test\ArchitectureTest;
use Tests\PhpAT\functional\php7\fixtures\ClassWithAnonymousClass;

class CompositionTest extends ArchitectureTest
{
    public function testSimpleInterfaceComposition(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePath('*/Composition/Composed.php'))
            ->mustImplement()
            ->classesThat(Selector::havePath('tests/functional/php7/fixtures/SimpleInterface.php'))
            ->build();
    }

    public function testMultipleInterfaceComposition(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePath('*/Composition/MultipleComposed.php'))
            ->mustImplement()
            ->classesThat(Selector::havePath('tests/functional/php7/fixtures/SimpleInterface.php'))
            ->andClassesThat(Selector::havePath('tests/functional/php7/fixtures/Composition/CompositionNamespaceSimpleInterface.php'))
            ->build();
    }

    public function testClassDoesNotImplement(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePath('*/Composition/CompositionNamespaceSimpleClass.php'))
            ->mustNotImplement()
            ->classesThat(Selector::havePath('tests/functional/php7/fixtures/SimpleInterface.php'))
            ->build();
    }

    public function testAnonymousClassInterfacesIgnored(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::haveClassName(ClassWithAnonymousClass::class))
            ->mustNotImplement()
            ->classesThat(Selector::havePath('tests/functional/php7/fixtures/SimpleInterface.php'))
            ->build();
    }
}
