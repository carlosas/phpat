<?php

namespace Tests\PHPat\unit\php81\architecture;

use PHPat\Rule\Rule;
use PHPat\Selector\SelectorInterface;
use PHPat\Test\ArchitectureTest;
use Tests\PHPat\unit\php81\fixtures\AnotherNamespace\ActivableEnum;
use Tests\PHPat\unit\php81\fixtures\AnotherNamespace\ActivableInterface;
use Tests\PHPat\unit\php81\fixtures\AnotherNamespace\EnumClassThree;
use Tests\PHPat\unit\php81\fixtures\AnotherNamespace\EnumClassTwo;
use Tests\PHPat\unit\php81\fixtures\ClassUsingEnum;
use Tests\PHPat\unit\php81\fixtures\EnumClassOne;

class EnumTest extends ArchitectureTest
{
    public function testEnumsAreCatched(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::haveClassName(ClassUsingEnum::class))
            ->mustDependOn()
            ->classesThat(SelectorInterface::haveClassName(EnumClassOne::class))
            ->andClassesThat(SelectorInterface::haveClassName(EnumClassTwo::class))
            ->andClassesThat(SelectorInterface::haveClassName(EnumClassThree::class))
            ->build();
    }

    public function testEnumsInterfacesAreCatched(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::haveClassName(ActivableEnum::class))
            ->mustImplement()
            ->classesThat(SelectorInterface::haveClassName(ActivableInterface::class))
            ->build();
    }

    public function testEnumsInterfacesCountAsDependencies(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::haveClassName(ActivableEnum::class))
            ->mustDependOn()
            ->classesThat(SelectorInterface::haveClassName(ActivableInterface::class))
            ->build();
    }
}
