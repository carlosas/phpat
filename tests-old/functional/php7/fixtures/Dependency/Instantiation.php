<?php

namespace Tests\PHPat\unit\php7\fixtures\Dependency;

use Tests\PHPat\unit\php7\fixtures\AnotherSimpleClass as AliasedClass;
use Tests\PHPat\unit\php7\fixtures\Inheritance;
use Tests\PHPat\unit\php7\fixtures\SimpleClass;

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
