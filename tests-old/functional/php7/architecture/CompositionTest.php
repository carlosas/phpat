<?php

namespace Tests\PHPat\unit\php7\architecture;

use PHPat\Rule\Rule;
use PHPat\Selector\SelectorInterface;
use PHPat\Test\ArchitectureTest;
use Tests\PHPat\unit\php7\fixtures\ClassWithAnonymousClass;

class CompositionTest extends ArchitectureTest
{
    public function testSimpleInterfaceComposition(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::havePath('Composition/Composed.php'))
            ->mustImplement()
            ->classesThat(SelectorInterface::havePath('SimpleInterface.php'))
            ->build();
    }

    public function testMultipleInterfaceComposition(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::havePath('Composition/MultipleComposed.php'))
            ->mustImplement()
            ->classesThat(SelectorInterface::havePath('SimpleInterface.php'))
            ->andClassesThat(SelectorInterface::havePath('Composition/CompositionNamespaceSimpleInterface.php'))
            ->build();
    }

    public function testClassDoesNotImplement(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::havePath('Composition/CompositionNamespaceSimpleClass.php'))
            ->mustNotImplement()
            ->classesThat(SelectorInterface::havePath('SimpleInterface.php'))
            ->build();
    }

    public function testAnonymousClassInterfacesIgnored(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::haveClassName(ClassWithAnonymousClass::class))
            ->mustNotImplement()
            ->classesThat(SelectorInterface::havePath('SimpleInterface.php'))
            ->build();
    }
}
