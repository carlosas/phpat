<?php

namespace Tests\PhpAT\functional\PHP7\fixtures\Dependency;

use Tests\PhpAT\functional\PHP7\fixtures\SimpleClass;
use Tests\PhpAT\functional\PHP7\fixtures\AnotherSimpleClass as AliasedClass;
use Tests\PhpAT\functional\PHP7\fixtures\Inheritance;

class DocBlock
{
    public function doSomething()
    {
        /** @var SimpleClass $a */
        $a = 1;
        /** @var AliasedClass $b */
        $b = 2;
        /** @var DependencyNamespaceSimpleClass $c */
        $c = 2;
        /** @var Inheritance\InheritanceNamespaceSimpleClass $d */
        $d = 2;
    }
}
