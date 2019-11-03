<?php

namespace Tests\PhpAT\functional\PHP7\fixtures\Dependency;

use Tests\PhpAT\functional\PHP7\fixtures\SimpleClass;

class MethodParameter
{
    public function doSomething(
        SimpleClass $simpleClass,
        DependencyNamespaceSimpleClass $dependencyNamespaceSimpleClass
    ) {
    }
}
