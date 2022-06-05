<?php

namespace Tests\PHPat\unit\php7\fixtures\Dependency;

use Tests\PHPat\unit\php7\fixtures\AnotherSimpleClass as AliasedClass;
use Tests\PHPat\unit\php7\fixtures\Inheritance;
use Tests\PHPat\unit\php7\fixtures\SimpleClass;

class MethodReturn
{
    public function doSomething(): SimpleClass
    {
    }

    public function doSomethingTwo(): AliasedClass
    {
    }

    public function doSomethingThree(): DependencyNamespaceSimpleClass
    {
    }

    public function doSomethingFour(): Inheritance\InheritanceNamespaceSimpleClass
    {
    }
}
