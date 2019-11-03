<?php

namespace Tests\PhpAT\functional\PHP7\fixtures\Dependency;

use Tests\PhpAT\functional\PHP7\fixtures\SimpleClass;

class Constructor
{
    public function __construct(
        SimpleClass $simpleClass,
        DependencyNamespaceSimpleClass $dependencyNamespaceSimpleClass
    ) {
    }
}
