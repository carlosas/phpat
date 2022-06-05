<?php

namespace Tests\PHPat\unit\php7\architecture;

use PHPat\Rule\Rule;
use PHPat\Selector\SelectorInterface;
use PHPat\Test\ArchitectureTest;
use Tests\PHPat\unit\php7\fixtures\ClassWithAnonymousClass;

class InheritanceTest extends ArchitectureTest
{
    public function testSameNamespaceInheritance(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::havePath('Inheritance/InheritanceNamespaceChild.php'))
            ->mustExtend()
            ->classesThat(SelectorInterface::havePath('Inheritance/InheritanceNamespaceSimpleClass.php'))
            ->build();
    }

    public function testDifferentNamespaceInheritance(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::havePath('Inheritance/Child.php'))
            ->mustExtend()
            ->classesThat(SelectorInterface::havePath('SimpleClass.php'))
            ->build();
    }

    public function testNotExtends(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::havePath('Inheritance/InheritanceNamespaceSimpleClass.php'))
            ->mustNotExtend()
            ->classesThat(SelectorInterface::havePath('SimpleClass.php'))
            ->build();
    }

    public function testAnonymousClassParentIgnored(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::haveClassName(ClassWithAnonymousClass::class))
            ->mustNotExtend()
            ->classesThat(SelectorInterface::havePath('SimpleClass.php'))
            ->build();
    }
}
