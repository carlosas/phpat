<?php

namespace Tests\PHPat\unit\php80\architecture;

use PHPat\Rule\Rule;
use PHPat\Selector\SelectorInterface;
use PHPat\Test\ArchitectureTest;
use Tests\PHPat\unit\php80\fixtures\ClassWithAttribute;
use Tests\PHPat\unit\php80\fixtures\ConstructorPromotionClass;
use Tests\PHPat\unit\php80\fixtures\DummyAttributeOne;
use Tests\PHPat\unit\php80\fixtures\DummyAttributeThree;
use Tests\PHPat\unit\php80\fixtures\DummyAttributeTwo;
use Tests\PHPat\unit\php80\fixtures\DummyException;
use Tests\PHPat\unit\php80\fixtures\MatchClass;
use Tests\PHPat\unit\php80\fixtures\NamedArgumentClass;
use Tests\PHPat\unit\php80\fixtures\SimpleClassFive;
use Tests\PHPat\unit\php80\fixtures\SimpleClassFour;
use Tests\PHPat\unit\php80\fixtures\SimpleClassOne;
use Tests\PHPat\unit\php80\fixtures\SimpleClassSix;
use Tests\PHPat\unit\php80\fixtures\SimpleClassThree;
use Tests\PHPat\unit\php80\fixtures\SimpleClassTwo;
use Tests\PHPat\unit\php80\fixtures\UnionClass;

class ClassDependencyTest extends ArchitectureTest
{
    public function testUnionTypesAreCatched(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::haveClassName(UnionClass::class))
            ->mustOnlyDependOn()
            ->classesThat(SelectorInterface::haveClassName(SimpleClassOne::class))
            ->andClassesThat(SelectorInterface::haveClassName(SimpleClassTwo::class))
            ->andClassesThat(SelectorInterface::haveClassName(SimpleClassThree::class))
            ->andClassesThat(SelectorInterface::haveClassName(SimpleClassFour::class))
            ->andClassesThat(SelectorInterface::haveClassName(SimpleClassFive::class))
            ->andClassesThat(SelectorInterface::haveClassName(SimpleClassSix::class))
            ->build();
    }

    public function testPromotedPropertiesAreCatched(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::haveClassName(ConstructorPromotionClass::class))
            ->mustOnlyDependOn()
            ->classesThat(SelectorInterface::haveClassName(SimpleClassOne::class))
            ->andClassesThat(SelectorInterface::haveClassName(SimpleClassTwo::class))
            ->andClassesThat(SelectorInterface::haveClassName(SimpleClassThree::class))
            ->andClassesThat(SelectorInterface::haveClassName(SimpleClassFour::class))
            ->build();
    }

    public function testNamedArgumentsAreCatched(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::haveClassName(NamedArgumentClass::class))
            ->mustOnlyDependOn()
            ->classesThat(SelectorInterface::haveClassName(UnionClass::class))
            ->andClassesThat(SelectorInterface::haveClassName(SimpleClassFive::class))
            ->build();
    }

    public function testMatchesWithThrowAreCatched(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::haveClassName(MatchClass::class))
            ->mustOnlyDependOn()
            ->classesThat(SelectorInterface::haveClassName(SimpleClassOne::class))
            ->andClassesThat(SelectorInterface::haveClassName(SimpleClassTwo::class))
            ->andClassesThat(SelectorInterface::haveClassName(DummyException::class))
            ->build();
    }

    public function testAttributesAreCatched(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::haveClassName(ClassWithAttribute::class))
            ->mustOnlyDependOn()
            ->classesThat(SelectorInterface::haveClassName(DummyAttributeOne::class))
            ->classesThat(SelectorInterface::haveClassName(DummyAttributeTwo::class))
            ->classesThat(SelectorInterface::haveClassName(DummyAttributeThree::class))
            ->build();
    }

    public function testInternalAttributeClassIsIgnored(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::haveClassName(DummyAttributeTwo::class))
            ->canOnlyDependOn()
            ->classesThat(SelectorInterface::haveClassName(DummyAttributeTwo::class))
            ->build();
    }
}
