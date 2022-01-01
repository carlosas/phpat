<?php

namespace Tests\PhpAT\unit\Rule\Assertion\Composition;

use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Rule\Assertion\Composition\MustOnlyImplement;
use Tests\PhpAT\unit\Rule\Assertion\AbstractAssertionTestCase;

class MustOnlyImplementTest extends AbstractAssertionTestCase
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
                [true, true, true]
            ],
            //it fails because it does not implement NotImplementedInterface
            [
                new FullClassName('Example\ClassExample'),
                [
                    new FullClassName('Example\InterfaceExample'),
                    new FullClassName('Example\AnotherInterface'),
                    new FullClassName('NotImplementedInterface')
                ],
                [],
                $this->getMap(),
                [true, true, false, true]
            ],
            //it fails because Example\AnotherInterface is also implemented
            [
                new FullClassName('Example\ClassExample'),
                [new FullClassName('Example\InterfaceExample')],
                [],
                $this->getMap(),
                [true, false]
            ],
            //it fails because it implements 2 that are not listed and does not implement NotARealInterface
            [
                new FullClassName('Example\ClassExample'),
                [new FullClassName('NotARealInterface')],
                [],
                $this->getMap(),
                [false, false, false]
            ],
        ];
    }
    protected function getTestedClassName(): string
    {
        return MustOnlyImplement::class;
    }
}
