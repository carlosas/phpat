<?php

namespace Tests\PhpAT\unit\Rule\Assertion\Composition;

use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Ast\RegexClassName;
use PhpAT\Rule\Assertion\Composition\MustImplement;
use Tests\PhpAT\unit\Rule\Assertion\AbstractAssertionTestCase;

class MustImplementTest extends AbstractAssertionTestCase
{
    public function dataProvider(): array
    {
        return [
            [
                new FullClassName('Example\ClassExample'),
                [new FullClassName('Example\InterfaceExample')],
                [],
                $this->getMap(),
                [true]
            ],
            [
                new FullClassName('Example\ClassExample'),
                [new FullClassName('Example\AnotherInterface')],
                [],
                $this->getMap(),
                [true]
            ],
            [
                new FullClassName('Example\ClassExample'),
                [
                    new FullClassName('Example\InterfaceExample'),
                    new FullClassName('Example\AnotherInterface')
                ],
                [],
                $this->getMap(),
                [true, true]
            ],
            //it fails because regex Example\Another* is excluded
            [
                new FullClassName('Example\ClassExample'),
                [
                    new FullClassName('Example\InterfaceExample'),
                    new FullClassName('Example\AnotherInterface')
                ],
                [new RegexClassName('Example\Another*')],
                $this->getMap(),
                [true, false]
            ],
            //it fails because NotARealInterface is not implemented
            [
                new FullClassName('Example\ClassExample'),
                [new FullClassName('NotARealInterface')],
                [],
                $this->getMap(),
                [false]
            ],
            //it fails twice because any of them are implemented
            [
                new FullClassName('Example\ClassExample'),
                [
                    new FullClassName('NopesOne'),
                    new FullClassName('NopesTwo')
                ],
                [],
                $this->getMap(),
                [false, false]
            ]
       ];
    }
    protected function getTestedClassName(): string
    {
        return MustImplement::class;
    }
}
