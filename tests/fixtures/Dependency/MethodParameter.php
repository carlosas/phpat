<?php

declare(strict_types=1);

namespace Tests\PHPat\fixtures\Dependency;

use Tests\PHPat\fixtures\AnotherSimpleClass as AliasedClass;
use Tests\PHPat\fixtures\SimpleClass;
use Tests\PHPat\unit\fixtures\Inheritance;

class MethodParameter
{
    public function doSomething(
        SimpleClass                                                       $simpleClass,
        AliasedClass                                                      $aliasedClass,
        DependencyNamespaceSimpleClass                                    $dependencyNamespaceSimpleClass,
        \Tests\PHPat\fixtures\Inheritance\InheritanceNamespaceSimpleClass $inheritanceNamespaceSimpleClass
    ): void {
    }
}
