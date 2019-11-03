<?php

namespace Tests\PhpAT\functional\PHP7\fixtures\Dependency;

use Tests\PhpAT\functional\PHP7\fixtures\SimpleClass;

class Instantiation
{
    public function doSomething()
    {
        $s1 = new SimpleClass();
        $s2 = new DependencyNamespaceSimpleClass();
    }
}
