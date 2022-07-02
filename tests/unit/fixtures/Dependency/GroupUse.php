<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\fixtures\Dependency;

use Tests\PHPat\unit\fixtures\AnotherSimpleClass as AliasedClass;
use Tests\PHPat\unit\fixtures\Inheritance;
use Tests\PHPat\unit\fixtures\SimpleClass;

class GroupUse
{
    public function doSomething(
        DependencyNamespaceSimpleClass $dependencyNamespaceSimpleClass,
        Inheritance\InheritanceNamespaceSimpleClass $inheritanceNamespaceSimpleClass
    ): void {
        /** @var SimpleClass $a */
        $a = 1;
        /** @var AliasedClass $b */
        $b = 2;
    }
}
