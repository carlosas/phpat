<?php

namespace Tests\PHPat\unit\php81\architecture;

use PHPat\Rule\Rule;
use PHPat\Selector\SelectorInterface;
use PHPat\Test\ArchitectureTest;
use Tests\PHPat\unit\php81\fixtures\AnotherNamespace\AnotherSimpleClass;
use Tests\PHPat\unit\php81\fixtures\AnotherNamespace\SimpleClass;
use Tests\PHPat\unit\php81\fixtures\ClassWithNewFeatures;
use Tests\PHPat\unit\php81\fixtures\DocBlockClass;
use Tests\PHPat\unit\php81\fixtures\EnumClassOne;

class ClassDependencyTest extends ArchitectureTest
{
    public function testReadonlyPromotedPropertiesAreCatched(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::haveClassName(ClassWithNewFeatures::class))
            ->mustDependOn()
            ->classesThat(SelectorInterface::haveClassName(EnumClassOne::class))
            ->build();
    }

    public function testIntersectedTypesAreCatched(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::haveClassName(ClassWithNewFeatures::class))
            ->andClassesThat(SelectorInterface::haveClassName(DocBlockClass::class))
            ->mustDependOn()
            ->classesThat(SelectorInterface::haveClassName(SimpleClass::class))
            ->andClassesThat(SelectorInterface::haveClassName(AnotherSimpleClass::class))
            ->build();
    }
}
