<?php

namespace Tests\PhpAT\functional\fixtures\Dependency;

use Tests\PhpAT\functional\fixtures\SimpleClass;
use Tests\PhpAT\functional\fixtures\AnotherSimpleClass as AliasedClass;
use Tests\PhpAT\functional\fixtures\Inheritance;

class DocBlock
{
    public function doSomething()
    {
        /** @var SimpleClass $a */
        $a = 1;
        /** @var AliasedClass $b */
        $b = 2;
        /** @var DependencyNamespaceSimpleClass $c */
        $c = 3;
        /** @var Inheritance\InheritanceNamespaceSimpleClass $d */
        $d = 4;
    }

    public function shouldNotBeCatched()
    {
        /** @var string $a */
        $a = 1;
        /** @var int $b */
        $b = 2;
        /** @var bool $c */
        $c = 3;
        /** @var null $d */
        $d = 4;

        $e = (int) is_null($d);
        $e = (int) \is_null($d);
    }
}
