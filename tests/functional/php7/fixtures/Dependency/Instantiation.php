<?php

namespace Tests\PhpAT\functional\php7\fixtures\Dependency;

use Tests\PhpAT\functional\php7\fixtures\SimpleClass;
use Tests\PhpAT\functional\php7\fixtures\AnotherSimpleClass as AliasedClass;
use Tests\PhpAT\functional\php7\fixtures\Inheritance;

class Instantiation
{
    public function doSomething()
    {
        $s1 = new SimpleClass();
        $s2 = new AliasedClass();
        $s3 = new DependencyNamespaceSimpleClass();
        $s4 = new Inheritance\InheritanceNamespaceSimpleClass();
    }
}
