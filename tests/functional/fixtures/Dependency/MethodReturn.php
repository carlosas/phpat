<?php

declare(strict_types=1);

namespace Tests\PHPat\functional\fixtures\Dependency;

use Tests\PHPat\functional\fixtures\AnotherSimpleClass as AliasedClass;
use Tests\PHPat\functional\fixtures\Inheritance;
use Tests\PHPat\functional\fixtures\SimpleClass;

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
