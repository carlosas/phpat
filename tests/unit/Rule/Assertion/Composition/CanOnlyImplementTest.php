<?php

namespace Tests\PhpAT\unit\Rule\Assertion\Composition;

use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Rule\Assertion\Composition\CanOnlyImplement;
use Tests\PhpAT\unit\Rule\Assertion\AbstractAssertionTestCase;

class CanOnlyImplementTest extends AbstractAssertionTestCase
{
    public function dataProvider(): array
    {
        return [
            [
                new FullClassName('Example\ClassExample'),
                [
                    new FullClassName('Example\InterfaceExample'),
                    new FullClassName('Example\AnotherInterface')
                ],
                [],
                $this->getMap(),
                [true]
            ],
            [
                new FullClassName('Example\ClassExample'),
                [
                    new FullClassName('Example\InterfaceExample'),
                    new FullClassName('Example\AnotherInterface'),
                    new FullClassName('NotImplementedInterface')
                ],
                [],
                $this->getMap(),
                [true]
            ],
            //it fails because Example\AnotherInterface is also implemented
            [
                new FullClassName('Example\ClassExample'),
                [new FullClassName('Example\InterfaceExample')],
                [],
                $this->getMap(),
                [false]
            ],
            //it fails because there are 2 interface implementations not listed
            [
                new FullClassName('Example\ClassExample'),
                [new FullClassName('NotARealInterface')],
                [],
                $this->getMap(),
                [false, false]
            ],
        ];
    }
    protected function getTestedClassName(): string
    {
        return CanOnlyImplement::class;
    }
}
