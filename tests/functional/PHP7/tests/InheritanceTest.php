<?php

namespace Tests\PhpAT\functional\PHP7\tests;

use PhpAT\Rule\Rule;
use PhpAT\Selector\Selector;
use PhpAT\Test\ArchitectureTest;

class InheritanceTest extends ArchitectureTest
{
    public function testSameNamespaceInheritance(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePath('Inheritance/InheritanceNamespaceChild.php'))
            ->mustExtend()
            ->classesThat(Selector::havePath('Inheritance/InheritanceNamespaceSimpleClass.php'))
            ->build();
    }

    public function testDifferentNamespaceInheritance(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePath('Inheritance/Child.php'))
            ->mustExtend()
            ->classesThat(Selector::havePath('SimpleClass.php'))
            ->build();
    }
}
