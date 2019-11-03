<?php

namespace Tests\PhpAT\functional\PHP7\fixtures\Dependency;

use Tests\PhpAT\functional\PHP7\fixtures\SimpleClass;
use Tests\PhpAT\functional\PHP7\fixtures\AnotherSimpleClass as AliasedClass;
use Tests\PhpAT\functional\PHP7\fixtures\Inheritance;

class MethodParameter
{
    public function doSomething(
        SimpleClass $simpleClass,
        AliasedClass $aliasedClass,
        DependencyNamespaceSimpleClass $dependencyNamespaceSimpleClass,
        Inheritance\InheritanceNamespaceSimpleClass $inheritanceNamespaceSimpleClass
    ) {
    }
}
