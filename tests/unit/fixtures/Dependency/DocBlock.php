<?php

declare(strict_types=1);

namespace Tests\PHPat\unit\fixtures\Dependency;

use Exception;
use Tests\PHPat\unit\fixtures\AnotherSimpleClass as AliasedClass;
use Tests\PHPat\unit\fixtures\CallableArgument;
use Tests\PHPat\unit\fixtures\CallableReturn;
use Tests\PHPat\unit\fixtures\DummyException;
use Tests\PHPat\unit\fixtures\GenericInner;
use Tests\PHPat\unit\fixtures\GenericOuter;
use Tests\PHPat\unit\fixtures\Inheritance;
use Tests\PHPat\unit\fixtures\SimpleClass;

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
        /** @var Inheritance\InheritanceNamespaceSimpleClass $d */
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
