<?php

namespace Tests\PHPat\unit\php7\architecture;

use PHPat\Rule\Rule;
use PHPat\Selector\SelectorInterface;
use PHPat\Test\ArchitectureTest;
use Tests\PHPat\unit\php7\fixtures\DocBlock;
use Tests\PHPat\unit\php7\fixtures\DummyExceptionClass;
use Tests\PHPat\unit\php7\fixtures\GenericInnerClass;
use Tests\PHPat\unit\php7\fixtures\GenericInnerClass2;
use Tests\PHPat\unit\php7\fixtures\GenericOuterClass;
use Tests\PHPat\unit\php7\fixtures\MethodParamClass;
use Tests\PHPat\unit\php7\fixtures\MethodReturnClass;
use Tests\PHPat\unit\php7\fixtures\MixinClass;
use Tests\PHPat\unit\php7\fixtures\ParamClass;
use Tests\PHPat\unit\php7\fixtures\ParamClass2;
use Tests\PHPat\unit\php7\fixtures\ParamClass3;
use Tests\PHPat\unit\php7\fixtures\PropertyClass;
use Tests\PHPat\unit\php7\fixtures\PropertyReadClass;
use Tests\PHPat\unit\php7\fixtures\PropertyWriteClass;
use Tests\PHPat\unit\php7\fixtures\ReturnClass;
use Tests\PHPat\unit\php7\fixtures\UnionClassOne;
use Tests\PHPat\unit\php7\fixtures\UnionClassTwo;
use Tests\PHPat\unit\php7\fixtures\VarClass;

class DocBlockTest extends ArchitectureTest
{
    public function testAllDocsAreCatched(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::haveClassName(DocBlock::class))
            ->mustDependOn()
            ->classesThat(SelectorInterface::haveClassName(PropertyClass::class))
            ->andClassesThat(SelectorInterface::haveClassName(PropertyWriteClass::class))
            ->andClassesThat(SelectorInterface::haveClassName(PropertyReadClass::class))
            ->andClassesThat(SelectorInterface::haveClassName(MethodReturnClass::class))
            ->andClassesThat(SelectorInterface::haveClassName(MethodParamClass::class))
            ->andClassesThat(SelectorInterface::haveClassName(MixinClass::class))
            ->andClassesThat(SelectorInterface::haveClassName(ParamClass::class))
            ->andClassesThat(SelectorInterface::haveClassName(ParamClass2::class))
            ->andClassesThat(SelectorInterface::haveClassName(ParamClass3::class))
            ->andClassesThat(SelectorInterface::haveClassName(ReturnClass::class))
            ->andClassesThat(SelectorInterface::haveClassName(GenericOuterClass::class))
            ->andClassesThat(SelectorInterface::haveClassName(GenericInnerClass::class))
            ->andClassesThat(SelectorInterface::haveClassName(GenericInnerClass2::class))
            ->andClassesThat(SelectorInterface::haveClassName(UnionClassOne::class))
            ->andClassesThat(SelectorInterface::haveClassName(UnionClassTwo::class))
            ->andClassesThat(SelectorInterface::haveClassName(DummyExceptionClass::class))
            ->andClassesThat(SelectorInterface::haveClassName(VarClass::class))
            ->build();
    }
}
