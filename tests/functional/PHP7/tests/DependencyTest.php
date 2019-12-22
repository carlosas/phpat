<?php

namespace Tests\PhpAT\functional\PHP7\tests;

use PhpAT\Rule\Rule;
use PhpAT\Selector\Selector;
use PhpAT\Test\ArchitectureTest;

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
            ->classesThat(Selector::havePath('AnotherSimpleClass.php'))
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
}
