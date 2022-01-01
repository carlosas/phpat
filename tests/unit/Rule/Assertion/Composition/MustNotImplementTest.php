<?php

namespace Tests\PhpAT\unit\Rule\Assertion\Composition;

use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Rule\Assertion\Composition\MustNotImplement;
use Tests\PhpAT\unit\Rule\Assertion\AbstractAssertionTestCase;

class MustNotImplementTest extends AbstractAssertionTestCase
{
    public function dataProvider(): array
    {
        return [
            [
                new FullClassName('Example\ClassExample'),
                [new FullClassName('NotARealInterface')],
                [],
                $this->getMap(),
                [true]
            ],
            [
                new FullClassName('Example\ClassExample'),
                [
                    new FullClassName('NopesOne'),
                    new FullClassName('NopesTwo')
                ],
                [],
                $this->getMap(),
                [true, true]
            ],
            //it fails because Example\InterfaceExample is implemented
            [
                new FullClassName('Example\ClassExample'),
                [new FullClassName('Example\InterfaceExample')],
                [],
                $this->getMap(),
                [false]
            ],
            //it fails twice because both are implemented
            [
                new FullClassName('Example\ClassExample'),
                [
                    new FullClassName('Example\InterfaceExample'),
                    new FullClassName('Example\AnotherInterface'),
                ],
                [],
                $this->getMap(),
                [false, false]
            ]
       ];
    }
    protected function getTestedClassName(): string
    {
        return MustNotImplement::class;
    }
}
