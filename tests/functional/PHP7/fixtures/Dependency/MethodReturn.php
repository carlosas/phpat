<?php

namespace Tests\PhpAT\functional\PHP7\fixtures\Dependency;

use Tests\PhpAT\functional\PHP7\fixtures\SimpleClass;
use Tests\PhpAT\functional\PHP7\fixtures\AnotherSimpleClass as AliasedClass;
use Tests\PhpAT\functional\PHP7\fixtures\Inheritance;

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
