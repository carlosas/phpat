<?php

namespace Tests\PhpAT\functional\php7\architecture;

use PhpAT\Rule\Rule;
use PhpAT\Selector\Selector;
use PhpAT\Test\ArchitectureTest;
use Tests\PhpAT\functional\php7\fixtures\ClassWithAnonymousClass;

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

    public function testNotExtends(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePath('Inheritance/InheritanceNamespaceSimpleClass.php'))
            ->mustNotExtend()
            ->classesThat(Selector::havePath('SimpleClass.php'))
            ->build();
    }

    public function testAnonymousClassParentIgnored(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::haveClassName(ClassWithAnonymousClass::class))
            ->mustNotExtend()
            ->classesThat(Selector::havePath('SimpleClass.php'))
            ->build();
    }
}
