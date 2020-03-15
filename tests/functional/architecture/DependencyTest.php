<?php

namespace Tests\PhpAT\functional\architecture;

use PHPAT\EventDispatcher\EventInterface;
use PhpAT\Rule\Rule;
use PhpAT\Selector\Selector;
use PhpAT\Test\ArchitectureTest;
use Tests\PhpAT\functional\fixtures\ClassWithAnonymousClass;
use Tests\PhpAT\functional\fixtures\DummyException;
use Tests\PhpAT\functional\fixtures\Inheritance\InheritanceNamespaceSimpleClass;
use Tests\PhpAT\functional\fixtures\SimpleClass;
use Tests\PhpAT\functional\fixtures\SimpleInterface;
use Tests\PhpAT\functional\fixtures\SimpleTrait;

final class DependencyTest extends ArchitectureTest
{
    public function testDirectDependency(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePath('Dependency/Constructor.php'))
            ->andClassesThat(Selector::havePath('Dependency/MethodParameter.php'))
            ->andClassesThat(Selector::havePath('Dependency/MethodReturn.php'))
            ->andClassesThat(Selector::havePath('Dependency/Instantiation.php'))
//            ->andClassesThat(Selector::havePath('Dependency/UnusedDeclaration.php'))
            ->andClassesThat(Selector::havePath('Dependency/DocBlock.php'))
            ->mustDependOn()
            ->classesThat(Selector::havePath('SimpleClass.php'))
            ->andClassesThat(Selector::havePath('AnotherSimpleClass.php'))
            ->andClassesThat(Selector::havePath('Dependency/DependencyNamespaceSimpleClass.php'))
            ->andClassesThat(Selector::havePath('Inheritance/InheritanceNamespaceSimpleClass.php'))
            ->build();
    }

    public function testNotDepends(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePath('Dependency/DependencyNamespaceSimpleClass.php'))
            ->mustNotDependOn()
            ->classesThat(Selector::havePath('SimpleClass.php'))
            ->build();
    }

    public function testOtherStuffIsNotResolvedAsClasses(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePath('Dependency/Others.php'))
            ->mustOnlyDependOn()
            ->classesThat(Selector::havePath('SimpleClass.php'))
            ->build();
    }

    public function testPredefinedClassesGetIgnored(): Rule
    {
        return $this->newRule
            ->andClassesThat(Selector::havePath('Dependency/Predefined.php'))
            ->mustOnlyDependOn()
            ->classesThat(Selector::haveClassName(\Exception::class)) //should warn
            ->classesThat(Selector::haveClassName('\Exception')) //should warn
            ->classesThat(Selector::haveClassName(\BadMethodCallException::class)) //should warn
            ->classesThat(Selector::haveClassName('\BadMethodCallException')) //should warn
            ->build();
    }

    public function testSelfAndStaticGetIgnored(): Rule
    {
        return $this->newRule
            ->andClassesThat(Selector::havePath('Dependency/SelfStatic.php'))
            ->mustOnlyDependOn()
            ->classesThat(Selector::haveClassName(SimpleClass::class))
            ->build();
    }

    public function testGroupUseDeclarationGetResolved(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePath('Dependency/GroupUse.php'))
            ->mustDependOn()
            ->classesThat(Selector::havePath('SimpleClass.php'))
            ->andClassesThat(Selector::havePath('AnotherSimpleClass.php'))
            ->andClassesThat(Selector::havePath('Dependency/DependencyNamespaceSimpleClass.php'))
            ->andClassesThat(Selector::havePath('Inheritance/InheritanceNamespaceSimpleClass.php'))
            ->build();
    }

    public function testAnonymousClassHasDependency(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::haveClassName(ClassWithAnonymousClass::class))
            ->mustOnlyDependOn()
            ->classesThat(Selector::havePath('SimpleClass.php'))
            ->classesThat(Selector::havePath('AnotherSimpleClass.php'))
            ->classesThat(Selector::havePath('SimpleInterface.php'))
            ->build();
    }

    public function testDocblocksDoNotDependOnOtherStuff(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePath('Dependency/DocBlock.php'))
            ->mustOnlyDependOn()
            ->classesThat(Selector::haveClassName(SimpleClass::class))
            ->andClassesThat(Selector::haveClassName('Tests\PhpAT\functional\fixtures\Simple*')) //should warn
            ->andClassesThat(Selector::havePath('AnotherSimpleClass.php'))
            ->andClassesThat(Selector::havePath('Dependency/DependencyNamespaceSimpleClass.php'))
            ->andClassesThat(Selector::haveClassName(InheritanceNamespaceSimpleClass::class))
            ->andClassesThat(Selector::haveClassName(DummyException::class))
            ->build();
    }
}
