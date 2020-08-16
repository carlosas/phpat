<?php

namespace Tests\PhpAT\functional\architecture;

use PHPAT\EventDispatcher\EventInterface;
use PhpAT\Rule\Rule;
use PhpAT\Selector\Selector;
use PhpAT\Test\ArchitectureTest;
use Tests\PhpAT\functional\fixtures\ClassWithAnonymousClass;
use Tests\PhpAT\functional\fixtures\Dependency\DocBlock;
use Tests\PhpAT\functional\fixtures\DummyException;
use Tests\PhpAT\functional\fixtures\GenericInner;
use Tests\PhpAT\functional\fixtures\GenericOuter;
use Tests\PhpAT\functional\fixtures\Inheritance\InheritanceNamespaceSimpleClass;
use Tests\PhpAT\functional\fixtures\SimpleClass;
use Tests\PhpAT\functional\fixtures\SimpleInterface;
use Tests\PhpAT\functional\fixtures\SimpleTrait;

class DependencyTest extends ArchitectureTest
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

    public function testCoreClassesGetIgnored(): Rule
    {
        return $this->newRule
            ->andClassesThat(Selector::havePath('Dependency/CoreAndExtensions.php'))
            ->canOnlyDependOn()
            ->classesThat(Selector::haveClassName(SimpleClass::class))
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
            ->andClassesThat(Selector::haveClassName(GenericInner::class))
            ->andClassesThat(Selector::haveClassName(GenericOuter::class))
            ->build();
    }

    public function testDocblocksSupportGenerics(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::haveClassName(DocBlock::class))
            ->mustDependOn()
            ->andClassesThat(Selector::haveClassName(GenericOuter::class))
            ->andClassesThat(Selector::haveClassName(GenericInner::class))
            ->build();
    }
}
