<?php

namespace Tests\PhpAT\functional\architecture;

use PHPAT\EventDispatcher\EventInterface;
use PhpAT\Rule\Rule;
use PhpAT\Selector\Selector;
use PhpAT\Test\ArchitectureTest;
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

    public function testPredefinedClassesGetIgnored(): Rule
    {
        return $this->newRule
            ->andClassesThat(Selector::havePath('Dependency/Predefined.php'))
            ->mustOnlyDependOn()
            ->classesThat(Selector::haveClassName(\Exception::class))
            ->classesThat(Selector::haveClassName('\Exception'))
            ->classesThat(Selector::haveClassName(\BadMethodCallException::class))
            ->classesThat(Selector::haveClassName('\BadMethodCallException'))
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

    public function testDocblocksDoNotDependOnOtherStuff(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::havePath('Dependency/DocBlock.php'))
            ->mustOnlyDependOn()
            ->classesThat(Selector::haveClassName('Tests\PhpAT\functional\fixtures\Simple*'))
            ->excludingClassesThat(Selector::haveClassName(SimpleTrait::class))
            ->excludingClassesThat(Selector::haveClassName(SimpleInterface::class))
            ->classesThat(Selector::haveClassName('Tests\PhpAT\functional\fixtures\Simple*'))
            ->andClassesThat(Selector::havePath('AnotherSimpleClass.php'))
            ->andClassesThat(Selector::havePath('Dependency/DependencyNamespaceSimpleClass.php'))
            ->andClassesThat(Selector::haveClassName(InheritanceNamespaceSimpleClass::class))
            ->build();
    }
}
