<?php

namespace Tests\PhpAT\functional\php81\architecture;

use PhpAT\Rule\Rule;
use PhpAT\Selector\Selector;
use PhpAT\Test\ArchitectureTest;
use Tests\PhpAT\functional\php81\fixtures\AnotherNamespace\ActivableEnum;
use Tests\PhpAT\functional\php81\fixtures\AnotherNamespace\ActivableInterface;
use Tests\PhpAT\functional\php81\fixtures\AnotherNamespace\EnumClassThree;
use Tests\PhpAT\functional\php81\fixtures\ClassUsingEnum;
use Tests\PhpAT\functional\php81\fixtures\ClassWithNewFeatures;
use Tests\PhpAT\functional\php81\fixtures\EnumClassOne;
use Tests\PhpAT\functional\php81\fixtures\AnotherNamespace\EnumClassTwo;

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
}
