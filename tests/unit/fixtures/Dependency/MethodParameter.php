<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\fixtures\Dependency;

use Tests\PHPat\unit\fixtures\AnotherSimpleClass as AliasedClass;
use Tests\PHPat\unit\fixtures\Inheritance;
use Tests\PHPat\unit\fixtures\SimpleClass;

class MethodParameter
{
    public function doSomething(
        SimpleClass $simpleClass,
        AliasedClass $aliasedClass,
        DependencyNamespaceSimpleClass $dependencyNamespaceSimpleClass,
        Inheritance\InheritanceNamespaceSimpleClass $inheritanceNamespaceSimpleClass
    ): void {
    }
}
