<?php

namespace Tests\PHPat\unit\php7\fixtures\Dependency;

use Tests\PHPat\unit\php7\fixtures\AnotherSimpleClass as AliasedClass;
use Tests\PHPat\unit\php7\fixtures\Inheritance;
use Tests\PHPat\unit\php7\fixtures\SimpleClass;

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
