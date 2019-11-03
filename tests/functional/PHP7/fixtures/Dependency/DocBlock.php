<?php

namespace Tests\PhpAT\functional\PHP7\fixtures\Dependency;

use Tests\PhpAT\functional\PHP7\fixtures\SimpleClass;

class DocBlock
{
    public function doSomething()
    {
        /** @var SimpleClass $a */
        $a = 1;
        /** @var DependencyNamespaceSimpleClass $b */
        $b = 2;
    }
}
