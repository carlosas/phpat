<?php

namespace Tests\PhpAT\functional\php7\architecture;

use PhpAT\Rule\Rule;
use PhpAT\Selector\Selector;
use PhpAT\Test\ArchitectureTest;
use Tests\PhpAT\functional\php7\fixtures\DocBlock;
use Tests\PhpAT\functional\php7\fixtures\DummyExceptionClass;
use Tests\PhpAT\functional\php7\fixtures\GenericInnerClass;
use Tests\PhpAT\functional\php7\fixtures\GenericInnerClass2;
use Tests\PhpAT\functional\php7\fixtures\GenericOuterClass;
use Tests\PhpAT\functional\php7\fixtures\MethodParamClass;
use Tests\PhpAT\functional\php7\fixtures\MethodReturnClass;
use Tests\PhpAT\functional\php7\fixtures\MixinClass;
use Tests\PhpAT\functional\php7\fixtures\ParamClass;
use Tests\PhpAT\functional\php7\fixtures\ParamClass2;
use Tests\PhpAT\functional\php7\fixtures\ParamClass3;
use Tests\PhpAT\functional\php7\fixtures\PropertyClass;
use Tests\PhpAT\functional\php7\fixtures\PropertyReadClass;
use Tests\PhpAT\functional\php7\fixtures\PropertyWriteClass;
use Tests\PhpAT\functional\php7\fixtures\ReturnClass;
use Tests\PhpAT\functional\php7\fixtures\UnionClassOne;
use Tests\PhpAT\functional\php7\fixtures\UnionClassTwo;
use Tests\PhpAT\functional\php7\fixtures\VarClass;

class DocBlockTest extends ArchitectureTest
{
    public function testAllDocsAreCatched(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::haveClassName(DocBlock::class))
            ->mustDependOn()
            ->classesThat(Selector::haveClassName(PropertyClass::class))
            ->andClassesThat(Selector::haveClassName(PropertyWriteClass::class))
            ->andClassesThat(Selector::haveClassName(PropertyReadClass::class))
            ->andClassesThat(Selector::haveClassName(MethodReturnClass::class))
            ->andClassesThat(Selector::haveClassName(MethodParamClass::class))
            ->andClassesThat(Selector::haveClassName(MixinClass::class))
            ->andClassesThat(Selector::haveClassName(ParamClass::class))
            ->andClassesThat(Selector::haveClassName(ParamClass2::class))
            ->andClassesThat(Selector::haveClassName(ParamClass3::class))
            ->andClassesThat(Selector::haveClassName(ReturnClass::class))
            ->andClassesThat(Selector::haveClassName(GenericOuterClass::class))
            ->andClassesThat(Selector::haveClassName(GenericInnerClass::class))
            ->andClassesThat(Selector::haveClassName(GenericInnerClass2::class))
            ->andClassesThat(Selector::haveClassName(UnionClassOne::class))
            ->andClassesThat(Selector::haveClassName(UnionClassTwo::class))
            ->andClassesThat(Selector::haveClassName(DummyExceptionClass::class))
            ->andClassesThat(Selector::haveClassName(VarClass::class))
            ->build();
    }
}
