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
            ->classesThat(Selector::havePathname('Dependency/Constructor.php'))
            ->andClassesThat(Selector::havePathname('Dependency/MethodParameter.php'))
            ->andClassesThat(Selector::havePathname('Dependency/MethodReturn.php'))
            ->andClassesThat(Selector::havePathname('Dependency/Instantiation.php'))
            //->andClassesThat(Selector::havePathname('Dependency/UnusedDeclaration.php'))
            //->andClassesThat(Selector::havePathname('Dependency/DocBlock.php'))
            ->mustDependOn()
            ->classesThat(Selector::havePathname('SimpleClass.php'))
            ->classesThat(Selector::havePathname('AnotherSimpleClass.php'))
            ->andClassesThat(Selector::havePathname('Dependency/DependencyNamespaceSimpleClass.php'))
            ->andClassesThat(Selector::havePathname('Inheritance/InheritanceNamespaceSimpleClass.php'))
            ->build();
    }
}
