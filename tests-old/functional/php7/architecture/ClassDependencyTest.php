<?php

namespace Tests\PHPat\unit\php7\architecture;

use PHPat\Rule\Rule;
use PHPat\Selector\SelectorInterface;
use PHPat\Test\ArchitectureTest;
use Tests\PHPat\unit\php7\fixtures\Dependency\ClassDependency;
use Tests\PHPat\unit\php7\fixtures\Dependency\ClassInterface;
use Tests\PHPat\unit\php7\fixtures\Dependency\ClassTrait;
use Tests\PHPat\unit\php7\fixtures\Dependency\ParentClass;

class ClassDependencyTest extends ArchitectureTest
{
    public function testAllClassDependenciesAreCatched(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::haveClassName(ClassDependency::class))
            ->mustOnlyDependOn()
            ->classesThat(SelectorInterface::haveClassName(ParentClass::class))
            ->andClassesThat(SelectorInterface::haveClassName(ClassInterface::class))
            ->andClassesThat(SelectorInterface::haveClassName(ClassTrait::class))
            ->build();
    }
}
