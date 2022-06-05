<?php

namespace Tests\PHPat\unit\php7\architecture;

use PHPat\Rule\Rule;
use PHPat\Selector\SelectorInterface;
use PHPat\Test\ArchitectureTest;
use Tests\PHPat\unit\FixtureOutOfPathOne;
use Tests\PHPat\unit\FixtureOutOfPathTwo;
use Tests\PHPat\unit\php7\fixtures\CallableArgument;
use Tests\PHPat\unit\php7\fixtures\CallableReturn;
use Tests\PHPat\unit\php7\fixtures\ClassWithAnonymousClass;
use Tests\PHPat\unit\php7\fixtures\ClassWithOutsideDependency;
use Tests\PHPat\unit\php7\fixtures\Dependency\DependencyOutOfClass;
use Tests\PHPat\unit\php7\fixtures\Dependency\DocBlock;
use Tests\PHPat\unit\php7\fixtures\DummyException;
use Tests\PHPat\unit\php7\fixtures\GenericInner;
use Tests\PHPat\unit\php7\fixtures\GenericOuter;
use Tests\PHPat\unit\php7\fixtures\Inheritance\InheritanceNamespaceSimpleClass;
use Tests\PHPat\unit\php7\fixtures\SimpleClass;

class DependencyTest extends ArchitectureTest
{
    public function testDirectDependency(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::havePath('Dependency/Constructor.php'))
            ->andClassesThat(SelectorInterface::havePath('Dependency/MethodParameter.php'))
            ->andClassesThat(SelectorInterface::havePath('Dependency/MethodReturn.php'))
            ->andClassesThat(SelectorInterface::havePath('Dependency/Instantiation.php'))
//            ->andClassesThat(Selector::havePath('Dependency/UnusedDeclaration.php'))
            ->andClassesThat(SelectorInterface::havePath('Dependency/DocBlock.php'))
            ->mustDependOn()
            ->classesThat(SelectorInterface::havePath('SimpleClass.php'))
            ->andClassesThat(SelectorInterface::havePath('AnotherSimpleClass.php'))
            ->andClassesThat(SelectorInterface::havePath('Dependency/DependencyNamespaceSimpleClass.php'))
            ->andClassesThat(SelectorInterface::havePath('Inheritance/InheritanceNamespaceSimpleClass.php'))
            ->build();
    }

    public function testNotDepends(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::havePath('Dependency/DependencyNamespaceSimpleClass.php'))
            ->mustNotDependOn()
            ->classesThat(SelectorInterface::havePath('SimpleClass.php'))
            ->build();
    }

    public function testOtherStuffIsNotResolvedAsClasses(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::havePath('Dependency/Others.php'))
            ->mustOnlyDependOn()
            ->classesThat(SelectorInterface::havePath('SimpleClass.php'))
            ->build();
    }

    public function testCoreClassesGetIgnored(): Rule
    {
        return $this->newRule
            ->andClassesThat(SelectorInterface::havePath('Dependency/CoreAndExtensions.php'))
            ->canOnlyDependOn()
            ->classesThat(SelectorInterface::haveClassName(SimpleClass::class))
            ->build();
    }

    public function testSelfAndStaticGetIgnored(): Rule
    {
        return $this->newRule
            ->andClassesThat(SelectorInterface::havePath('Dependency/SelfStatic.php'))
            ->mustOnlyDependOn()
            ->classesThat(SelectorInterface::haveClassName(SimpleClass::class))
            ->build();
    }

    public function testGroupUseDeclarationGetResolved(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::havePath('Dependency/GroupUse.php'))
            ->mustDependOn()
            ->classesThat(SelectorInterface::havePath('SimpleClass.php'))
            ->andClassesThat(SelectorInterface::havePath('AnotherSimpleClass.php'))
            ->andClassesThat(SelectorInterface::havePath('Dependency/DependencyNamespaceSimpleClass.php'))
            ->andClassesThat(SelectorInterface::havePath('Inheritance/InheritanceNamespaceSimpleClass.php'))
            ->build();
    }

    public function testAnonymousClassHasDependency(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::haveClassName(ClassWithAnonymousClass::class))
            ->mustOnlyDependOn()
            ->classesThat(SelectorInterface::havePath('SimpleClass.php'))
            ->classesThat(SelectorInterface::havePath('AnotherSimpleClass.php'))
            ->classesThat(SelectorInterface::havePath('SimpleInterface.php'))
            ->build();
    }

    public function testDocblocksDoNotDependOnOtherStuff(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::havePath('Dependency/DocBlock.php'))
            ->mustOnlyDependOn()
            ->classesThat(SelectorInterface::haveClassName(SimpleClass::class))
            ->andClassesThat(SelectorInterface::havePath('AnotherSimpleClass.php'))
            ->andClassesThat(SelectorInterface::havePath('Dependency/DependencyNamespaceSimpleClass.php'))
            ->andClassesThat(SelectorInterface::haveClassName(InheritanceNamespaceSimpleClass::class))
            ->andClassesThat(SelectorInterface::haveClassName(DummyException::class))
            ->andClassesThat(SelectorInterface::haveClassName(GenericInner::class))
            ->andClassesThat(SelectorInterface::haveClassName(GenericOuter::class))
            ->andClassesThat(SelectorInterface::haveClassName(CallableArgument::class))
            ->andClassesThat(SelectorInterface::haveClassName(CallableReturn::class))
            ->build();
    }

    public function testDocblocksHaveSupportForCallableTypes(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::haveClassName(DocBlock::class))
            ->mustDependOn()
            ->andClassesThat(SelectorInterface::haveClassName(CallableArgument::class))
            ->andClassesThat(SelectorInterface::haveClassName(CallableReturn::class))
            ->build();
    }

    public function testDocblocksSupportGenerics(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::haveClassName(DocBlock::class))
            ->mustDependOn()
            ->classesThat(SelectorInterface::haveClassName(GenericOuter::class))
            ->andClassesThat(SelectorInterface::haveClassName(GenericInner::class))
            ->build();
    }

    public function testDependencyOutsideOfClassGetsIgnored(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::haveClassName(DependencyOutOfClass::class))
            ->mustNotDependOn()
            ->classesThat(SelectorInterface::haveClassName(SimpleClass::class))
            ->build();
    }

    public function testClassOutsideOfPathGetsSelectedInInclusionAndExclusion(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::haveClassName(ClassWithOutsideDependency::class))
            ->mustNotDependOn()
            ->classesThat(SelectorInterface::haveClassName('Tests\PHPat\functional\FixtureOutOfPath*'))
            ->excludingClassesThat(SelectorInterface::haveClassName(FixtureOutOfPathOne::class))
            ->excludingClassesThat(SelectorInterface::haveClassName(FixtureOutOfPathTwo::class))
            ->build();
    }
}
