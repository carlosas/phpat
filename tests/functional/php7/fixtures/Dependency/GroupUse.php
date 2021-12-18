<?php

namespace Tests\PhpAT\functional\php7\fixtures\Dependency;

use Tests\PhpAT\functional\php7\fixtures\{SimpleClass, Inheritance, AnotherSimpleClass as AliasedClass};

class GroupUse
{
    public function doSomething(
        DependencyNamespaceSimpleClass $dependencyNamespaceSimpleClass,
        Inheritance\InheritanceNamespaceSimpleClass $inheritanceNamespaceSimpleClass
    ) {
        /** @var SimpleClass $a */
        $a = 1;
        /** @var AliasedClass $b */
        $b = 2;
    }
}
