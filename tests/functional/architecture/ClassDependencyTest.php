<?php

namespace Tests\PhpAT\functional\architecture;

use PhpAT\Rule\Rule;
use PhpAT\Selector\Selector;
use PhpAT\Test\ArchitectureTest;
use Tests\PhpAT\functional\fixtures\Dependency\ClassDependency;
use Tests\PhpAT\functional\fixtures\Dependency\ClassInterface;
use Tests\PhpAT\functional\fixtures\Dependency\ClassTrait;
use Tests\PhpAT\functional\fixtures\Dependency\ParentClass;

class ClassDependencyTest extends ArchitectureTest
{
    public function testAllClassDependenciesAreCatched(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::haveClassName(ClassDependency::class))
            ->mustOnlyDependOn()
            ->classesThat(Selector::haveClassName(ParentClass::class))
            ->andClassesThat(Selector::haveClassName(ClassInterface::class))
            ->andClassesThat(Selector::haveClassName(ClassTrait::class))
            ->build();
    }
}
