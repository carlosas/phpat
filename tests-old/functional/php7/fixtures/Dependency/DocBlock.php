<?php

namespace Tests\PHPat\unit\php7\fixtures\Dependency;

use Tests\PHPat\unit\php7\fixtures\AnotherSimpleClass as AliasedClass;
use Tests\PHPat\unit\php7\fixtures\CallableArgument;
use Tests\PHPat\unit\php7\fixtures\CallableReturn;
use Tests\PHPat\unit\php7\fixtures\DummyException;
use Tests\PHPat\unit\php7\fixtures\GenericInner;
use Tests\PHPat\unit\php7\fixtures\GenericOuter;
use Tests\PHPat\unit\php7\fixtures\Inheritance;
use Tests\PHPat\unit\php7\fixtures\SimpleClass;

class DocBlock
{
    /**
     * @throws DummyException
     */
    public function doSomething()
    {
        /** @var SimpleClass[] $a */
        $a = 1;
        /** @var AliasedClass $b */
        $b = 2;
        /** @var DependencyNamespaceSimpleClass $c */
        $c = 3;
        /** @var Inheritance\InheritanceNamespaceSimpleClass $d */
        $d = 4;
    }

    /**
     * @throws \Exception
     */
    public function shouldNotBeCatched()
    {
        /** @var string $a */
        $a = 1;
        /** @var int $b */
        $b = 2;
        /** @var \int $b */
        $b = 2;
        /** @var bool $c */
        $c = 3;
        /** @var null $d */
        $d = 4;
    }

    /** @param (callable(CallableArgument): CallableReturn) $genericArgument */
    public function acceptsCallable(callable $callableArgument): void
    {
    }

    /** @param GenericOuter<GenericInner> $genericArgument */
    public function classGeneric($genericArgument): void
    {
    }

    /** @param array<GenericInner> $genericArgument */
    public function arrayGeneric($genericArgument): void
    {
    }
}
