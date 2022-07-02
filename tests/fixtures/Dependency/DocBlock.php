<?php

declare(strict_types=1);

namespace Tests\PHPat\fixtures\Dependency;

use Exception;
use Tests\PHPat\fixtures\AnotherSimpleClass as AliasedClass;
use Tests\PHPat\fixtures\DummyException;
use Tests\PHPat\fixtures\GenericInner;
use Tests\PHPat\fixtures\GenericOuter;
use Tests\PHPat\fixtures\SimpleClass;
use Tests\PHPat\unit\fixtures\Inheritance;

class DocBlock
{
    /**
     * @throws DummyException
     */
    public function doSomething(): void
    {
        /** @var SimpleClass[] $a */
        $a = 1;
        /** @var AliasedClass $b */
        $b = 2;
        /** @var DependencyNamespaceSimpleClass $c */
        $c = 3;
        /** @var \Tests\PHPat\fixtures\Inheritance\InheritanceNamespaceSimpleClass $d */
        $d = 4;
    }

    /**
     * @throws Exception
     */
    public function shouldNotBeCatched(): void
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
