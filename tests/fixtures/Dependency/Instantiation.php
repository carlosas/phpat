<?php

declare(strict_types=1);

namespace Tests\PHPat\fixtures\Dependency;

use Tests\PHPat\fixtures\AnotherSimpleClass as AliasedClass;
use Tests\PHPat\fixtures\SimpleClass;
use Tests\PHPat\unit\fixtures\Inheritance;

class Instantiation
{
    public function doSomething(): void
    {
        $s1 = new SimpleClass();
        $s2 = new AliasedClass();
        $s3 = new DependencyNamespaceSimpleClass();
        $s4 = new \Tests\PHPat\fixtures\Inheritance\InheritanceNamespaceSimpleClass();
    }
}
