<?php

namespace Tests\PHPat\unit\php7\architecture;

use PHPat\Rule\Rule;
use PHPat\Selector\SelectorInterface;
use PHPat\Test\ArchitectureTest;
use Tests\PHPat\unit\php7\fixtures\Dependency\ClosureReturnClass;
use Tests\PHPat\unit\php7\fixtures\Dependency\ClosureVarClass;
use Tests\PHPat\unit\php7\fixtures\Dependency\ConstClass;
use Tests\PHPat\unit\php7\fixtures\Dependency\ConstructException;
use Tests\PHPat\unit\php7\fixtures\Dependency\ConstructParamClassOne;
use Tests\PHPat\unit\php7\fixtures\Dependency\ConstructParamClassTwo;
use Tests\PHPat\unit\php7\fixtures\Dependency\MethodDependency;
use Tests\PHPat\unit\php7\fixtures\Dependency\ParamClassOne;
use Tests\PHPat\unit\php7\fixtures\Dependency\ParamClassTwo;
use Tests\PHPat\unit\php7\fixtures\Dependency\ReturnClass;
use Tests\PHPat\unit\php7\fixtures\Dependency\ReturnVarClass;
use Tests\PHPat\unit\php7\fixtures\Dependency\StaticMethodClass;
use Tests\PHPat\unit\php7\fixtures\Dependency\VarClass;

class MethodDependencyTest extends ArchitectureTest
{
    public function testAllMethodDependenciesAreCatched(): Rule
    {
        return $this->newRule
            ->classesThat(SelectorInterface::haveClassName(MethodDependency::class))
            ->mustOnlyDependOn()
            ->classesThat(SelectorInterface::haveClassName(ConstructParamClassOne::class))
            ->andClassesThat(SelectorInterface::haveClassName(ConstructParamClassTwo::class))
            ->andClassesThat(SelectorInterface::haveClassName(ConstructException::class))
            ->andClassesThat(SelectorInterface::haveClassName(ParamClassOne::class))
            ->andClassesThat(SelectorInterface::haveClassName(ParamClassTwo::class))
            ->andClassesThat(SelectorInterface::haveClassName(StaticMethodClass::class))
            ->andClassesThat(SelectorInterface::haveClassName(ReturnClass::class))
            ->andClassesThat(SelectorInterface::haveClassName(ReturnVarClass::class))
            ->andClassesThat(SelectorInterface::haveClassName(VarClass::class))
            ->andClassesThat(SelectorInterface::haveClassName(ConstClass::class))
            ->andClassesThat(SelectorInterface::haveClassName(ClosureVarClass::class))
            ->andClassesThat(SelectorInterface::haveClassName(ClosureReturnClass::class))
            ->build();
    }
}
