<?php

declare(strict_types=1);

namespace Tests\PHPat\fixtures\Dependency;

use Tests\PHPat\fixtures\AnotherSimpleClass as AliasedClass;
use Tests\PHPat\fixtures\SimpleClass;
use Tests\PHPat\unit\fixtures\Inheritance;

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

    public function doSomethingFour(): \Tests\PHPat\fixtures\Inheritance\InheritanceNamespaceSimpleClass
    {
    }
}
