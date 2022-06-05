<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\fixtures\Dependency;

use Tests\PHPat\unit\fixtures\AnotherSimpleClass as AliasedClass;
use Tests\PHPat\unit\fixtures\Inheritance;
use Tests\PHPat\unit\fixtures\SimpleClass;

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
