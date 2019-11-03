<?php

namespace Tests\PhpAT\functional\PHP7\fixtures\Dependency;

use Tests\PhpAT\functional\PHP7\fixtures\SimpleClass;

class MethodReturn
{
    public function doSomething(): SimpleClass
    {
    }

    public function doSomethingElse(): DependencyNamespaceSimpleClass
    {
    }
}
