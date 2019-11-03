<?php

namespace Tests\PhpAT\functional\PHP7\tests;

use PhpAT\Rule\Rule;
use PhpAT\Selector\Selector;
use PhpAT\Test\ArchitectureTest;

class InheritanceTest extends ArchitectureTest
{
    public function testDifferentNamespaceInheritance(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePathname('Inheritance/Child.php'))
            ->shouldExtend()
            ->classesThat(Selector::havePathname('SimpleClass.php'))
            ->build();
    }

    public function testSameNamespaceInheritance(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePathname('Inheritance/InheritanceNamespaceChild.php'))
            ->shouldExtend()
            ->classesThat(Selector::havePathname('Inheritance/InheritanceNamespaceSimpleClass.php'))
            ->build();
    }
}
