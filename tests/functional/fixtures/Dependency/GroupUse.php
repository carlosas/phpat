<?php

declare(strict_types=1);

namespace Tests\PHPat\functional\fixtures\Dependency;

use Tests\PHPat\functional\fixtures\AnotherSimpleClass as AliasedClass;
use Tests\PHPat\functional\fixtures\Inheritance;
use Tests\PHPat\functional\fixtures\SimpleClass;

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
