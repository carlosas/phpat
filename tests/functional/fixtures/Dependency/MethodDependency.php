<?php

namespace Tests\PHPat\functional\fixtures\Dependency;

class MethodDependency
{
    public function __construct(ConstructParamClassOne $one, ConstructParamClassTwo $two) {
        throw new ConstructException();
    }

    private function methodOne(
        ParamClassOne $one,
        ParamClassTwo $two
    ): ReturnClass
    {
        $varOne = new VarClass();
        $varTwo = ConstClass::CONSTANT;
        StaticMethodClass::someMethod();
        $closure = function () {
                $closureVar = new ClosureVarClass();
                return new ClosureReturnClass();
            };

        return new ReturnVarClass();
    }
}

class ConstructParamClassOne {}

class ConstructParamClassTwo {}

class ConstructException extends \Exception {}

class ParamClassOne {}

class ParamClassTwo {}

class ReturnClass {}

class ReturnVarClass {}

class VarClass {}

class ConstClass { public const CONSTANT = 'value'; }

class StaticMethodClass { public static function someMethod() {} }

class ClosureVarClass {}

class ClosureReturnClass {}
