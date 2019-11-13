<?php declare(strict_types=1);

namespace Tests\PhpAT\functional\PHP7\tests;

use PhpAT\Rule\Rule;
use PhpAT\Selector\Selector;
use PhpAT\Test\ArchitectureTest;

class CompositionTest extends ArchitectureTest
{
    public function testSimpleInterfaceComposition(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePath('Composition/Composed.php'))
            ->mustImplement()
            ->classesThat(Selector::havePath('SimpleInterface.php'))
            ->build();
    }

    public function testMultipleInterfaceComposition(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePath('Composition/MultipleComposed.php'))
            ->mustImplement()
            ->classesThat(Selector::havePath('SimpleInterface.php'))
            ->andClassesThat(Selector::havePath('Composition/CompositionNamespaceSimpleInterface.php'))
            ->build();
    }

    public function testClassDoesNotImplement(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePath('Composition/CompositionNamespaceSimpleClass.php'))
            ->mustNotImplement()
            ->classesThat(Selector::havePath('SimpleInterface.php'))
            ->build();
    }
}
