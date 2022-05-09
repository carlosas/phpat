<?php

namespace Tests\PhpAT\functional\fixtures;

/**
 * @property PropertyClass $myProperty
 * @property-write PropertyWriteClass $myProperty2
 * @property-read PropertyReadClass $myProperty3
 * @method MethodReturnClass someMethod(MethodParamClass $m)
 * @mixin MixinClass
 */
class DocBlock
{
    /**
     * @param ParamClass $p
     * @param ParamClass2 $p2 Parameter with description
     * @param \Tests\PhpAT\functional\php7\fixtures\ParamClass3 $p3
     * @param GenericOuterClass<GenericInnerClass> $p4
     * @param array<GenericInnerClass2> $p5
     * @param UnionClassOne|UnionClassTwo $p6
     * @return ReturnClass nicedescription
     * @throws DummyExceptionClass
     */
    public function methodOne($p, $p2, $p3, $p4, $p5, $p6)
    {
        /** @var VarClass $v */
        $v = null;
    }
}

class PropertyClass {}

class PropertyWriteClass {}

class PropertyReadClass {}

class MethodReturnClass {}

class MethodParamClass {}

class MixinClass {}

class ParamClass {}

class ParamClass2 {}

class ParamClass3 {}

class ReturnClass {}

class GenericOuterClass {}

class GenericInnerClass {}

class GenericInnerClass2 {}

class UnionClassOne {}

class UnionClassTwo {}

class DummyExceptionClass {}

class VarClass {}
