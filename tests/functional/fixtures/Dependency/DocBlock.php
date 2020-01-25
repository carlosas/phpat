<?php

namespace Tests\PhpAT\functional\fixtures\Dependency;

use Tests\PhpAT\functional\fixtures\SimpleClass;
use Tests\PhpAT\functional\fixtures\AnotherSimpleClass as AliasedClass;
use Tests\PhpAT\functional\fixtures\Inheritance;

class DocBlock
{
    public function doSomething()
    {
        /** @var SimpleClass $a */
        $a = 1;
        /** @var AliasedClass $b */
        $b = 2;
        /** @var DependencyNamespaceSimpleClass $c */
        $c = 3;
        /** @var Inheritance\InheritanceNamespaceSimpleClass $d */
        $d = 4;
    }
}
