<?php

declare(strict_types=1);

namespace Tests\PHPat\fixtures\Dependency;

use Tests\PHPat\fixtures\AnotherSimpleClass as AliasedClass;
use Tests\PHPat\fixtures\SimpleClass;
use Tests\PHPat\unit\fixtures\Inheritance;

class GroupUse
{
    public function doSomething(
        DependencyNamespaceSimpleClass                                    $dependencyNamespaceSimpleClass,
        \Tests\PHPat\fixtures\Inheritance\InheritanceNamespaceSimpleClass $inheritanceNamespaceSimpleClass
    ): void {
        /** @var SimpleClass $a */
        $a = 1;
        /** @var AliasedClass $b */
        $b = 2;
    }
}
