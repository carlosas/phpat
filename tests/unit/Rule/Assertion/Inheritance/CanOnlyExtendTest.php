<?php

namespace Tests\PhpAT\unit\Rule\Assertion\Inheritance;

use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Rule\Assertion\Inheritance\CanOnlyExtend;
use Tests\PhpAT\unit\Rule\Assertion\AbstractAssertionTestCase;

class CanOnlyExtendTest extends AbstractAssertionTestCase
{
    public function dataProvider(): array
    {
        return [
            [
                new FullClassName('Example\ClassExample'),
                [new FullClassName('Example\ParentClassExample')],
                [],
                $this->getMap(),
                [true]
            ],
            //it fails because it does not extend Example\AnotherClass
            [
                new FullClassName('Example\ClassExample'),
                [new FullClassName('Example\AnotherClass')],
                [],
                $this->getMap(),
                [false]
            ],
            //it fails because it extends from a class not listed
            [
                new FullClassName('Example\ClassExample'),
                [new FullClassName('NotARealParent')],
                [],
                $this->getMap(),
                [false]
            ],
        ];
    }
    protected function getTestedClassName(): string
    {
        return CanOnlyExtend::class;
    }
}
