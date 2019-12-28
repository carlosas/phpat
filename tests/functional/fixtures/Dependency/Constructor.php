<?php

namespace Tests\PhpAT\functional\fixtures\Dependency;

use Tests\PhpAT\functional\fixtures\SimpleClass;
use Tests\PhpAT\functional\fixtures\AnotherSimpleClass as AliasedClass;
use Tests\PhpAT\functional\fixtures\Inheritance;

class Constructor
{
    public function __construct(
        SimpleClass $simpleClass,
        AliasedClass $aliasedClass,
        DependencyNamespaceSimpleClass $dependencyNamespaceSimpleClass,
        Inheritance\InheritanceNamespaceSimpleClass $inheritanceNamespaceSimpleClass
    ) {
    }
}
