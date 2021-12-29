<?php

namespace Tests\PhpAT\functional\php81\architecture;

use PhpAT\Rule\Rule;
use PhpAT\Selector\Selector;
use PhpAT\Test\ArchitectureTest;
use Tests\PhpAT\functional\php81\fixtures\AnotherNamespace\ActivableInterface;
use Tests\PhpAT\functional\php81\fixtures\AnotherNamespace\EnumClassThree;
use Tests\PhpAT\functional\php81\fixtures\ClassUsingEnum;
use Tests\PhpAT\functional\php81\fixtures\EnumClassOne;
use Tests\PhpAT\functional\php81\fixtures\AnotherNamespace\EnumClassTwo;

class EnumTest extends ArchitectureTest
{
    public function testEnumsAreCatched(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::haveClassName(ClassUsingEnum::class))
            ->mustDependOn()
            ->classesThat(Selector::haveClassName(EnumClassOne::class))
            ->andClassesThat(Selector::haveClassName(EnumClassTwo::class))
            ->andClassesThat(Selector::haveClassName(EnumClassThree::class))
            ->build();
    }
}
