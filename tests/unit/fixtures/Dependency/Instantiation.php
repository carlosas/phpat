<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\fixtures\Dependency;

use Tests\PHPat\unit\fixtures\AnotherSimpleClass as AliasedClass;
use Tests\PHPat\unit\fixtures\Inheritance;
use Tests\PHPat\unit\fixtures\SimpleClass;

class Instantiation
{
    public function doSomething(): void
    {
        $s1 = new SimpleClass();
        $s2 = new AliasedClass();
        $s3 = new DependencyNamespaceSimpleClass();
        $s4 = new Inheritance\InheritanceNamespaceSimpleClass();
    }
}
