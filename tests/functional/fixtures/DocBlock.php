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
    //TODO * @uses UsesClass to do something
    //     * @uses UsesClass2::usesMethod() to do something else
    /**
     * @param ParamClass $p
     * @param ParamClass2 $p2 Parameter with description
     * @param GenericOuterClass<GenericInnerClass> $p3
     * @param array<GenericInnerClass2> $p4
     * @return ReturnClass nicedescription
     * @throws DummyExceptionClass
     */
    public function methodOne($p, $p2, $p3, $p4)
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

class ReturnClass {}

class GenericOuterClass {}

class GenericInnerClass {}

class GenericInnerClass2 {}

class UsesClass {}

class UsesClass2 {
    public function usesMethod() {}
}

class DummyExceptionClass {}

class VarClass {}
