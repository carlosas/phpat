<?php

declare(strict_types=1);

namespace PhpAT\Test;

use PhpAT\DumbShit;
use PhpAT\Rule\Assertion\Dependency;
use PhpAT\Selector\ClassImplements;
use PhpAT\Selector\Classname;
use PhpAT\SimpleClass;
use PhpAT\SomeInterface;

class TestParser
{
    public function __invoke()
    {
        return [
            [
                'subjects' => [(new Classname(SimpleClass::class))],
                'assertion' => Dependency\MustNotDepend\MustNotDepend::class,
                'targets' => [
                    (new Classname(DumbShit::class)),
                    (new ClassImplements(SomeInterface::class))
                ],
            ],
        ];
    }
}
