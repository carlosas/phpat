<?php

namespace Tests\PhpAT\functional\php81\architecture;

use PhpAT\Rule\Rule;
use PhpAT\Selector\Selector;
use PhpAT\Test\ArchitectureTest;
use Tests\PhpAT\functional\php81\fixtures\AnotherNamespace\SimpleClass;
use Tests\PhpAT\functional\php81\fixtures\AnotherNamespace\AnotherSimpleClass;
use Tests\PhpAT\functional\php81\fixtures\ClassWithNewFeatures;
use Tests\PhpAT\functional\php81\fixtures\DocBlockClass;
use Tests\PhpAT\functional\php81\fixtures\EnumClassOne;

class ClassDependencyTest extends ArchitectureTest
{
    public function testReadonlyPromotedPropertiesAreCatched(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::haveClassName(ClassWithNewFeatures::class))
            ->mustDependOn()
            ->classesThat(Selector::haveClassName(EnumClassOne::class))
            ->build();
    }

    public function testIntersectedTypesAreCatched(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::haveClassName(ClassWithNewFeatures::class))
            ->andClassesThat(Selector::haveClassName(DocBlockClass::class))
            ->mustDependOn()
            ->classesThat(Selector::haveClassName(SimpleClass::class))
            ->andClassesThat(Selector::haveClassName(AnotherSimpleClass::class))
            ->build();
    }
}
