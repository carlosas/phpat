<?php

declare(strict_types=1);

namespace PhpAT\Test;

use PhpAT\DumbShit;
use PhpAT\DumbShitTwo;
use PhpAT\Rule\Assertion\Composition;
use PhpAT\Rule\Assertion\Dependency;
use PhpAT\Rule\Assertion\Inheritance;
use PhpAT\Rule\Assertion\Mixin;
use PhpAT\Selector\Classname;
use PhpAT\SimpleClass;
use PHPStan\Rules\Rule as PHPStanRule;

class TestParser
{
//    /** @var null|class-string<PHPStanRule> */
//    private ?string $assertion;
//    /** @var array<class-string> */
//    private array $origin = [];
//    /** @var array<class-string> */
//    private array $originExclude = [];
//    /** @var array<class-string> */
//    private array $destination = [];
//    /** @var array<class-string> */
//    private array $destinationExclude = [];

    public function __invoke()
    {
        return [
            [
                'subjects' => [(new Classname(SimpleClass::class))],
                'assertion' => Dependency\MustNotDepend\MustNotDepend::class,
                'targets' => [DumbShit::class, DumbShitTwo::class],
            ],
        ];
    }
}
