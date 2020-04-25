<?php

namespace Tests\PhpAT\unit\Rule\Assertion\Composition;

use PhpAT\Parser\FullClassName;
use PhpAT\Parser\RegexClassName;
use PhpAT\Rule\Assertion\Composition\MustImplement;
use Tests\PhpAT\unit\Rule\Assertion\AbstractAssertionTestCase;

class MustImplementTest extends AbstractAssertionTestCase
{
    protected function getTestedClassName(): string
    {
        return MustImplement::class;
    }

    public function dataProvider(): array
    {
        return [
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('Example\InterfaceExample')],
                [],
                $this->getMap(),
                [true]
            ],
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('Example\AnotherInterface')],
                [],
                $this->getMap(),
                [true]
            ],
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [
                    FullClassName::createFromFQCN('Example\InterfaceExample'),
                    FullClassName::createFromFQCN('Example\AnotherInterface')
                ],
                [],
                $this->getMap(),
                [true, true]
            ],
            //it fails because regex Example\Another* is excluded
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [
                    FullClassName::createFromFQCN('Example\InterfaceExample'),
                    FullClassName::createFromFQCN('Example\AnotherInterface')
                ],
                [new RegexClassName('Example\Another*')],
                $this->getMap(),
                [true, false]
            ],
            //it fails because NotARealInterface is not implemented
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('NotARealInterface')],
                [],
                $this->getMap(),
                [false]
            ],
            //it fails twice because any of them are implemented
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [
                    FullClassName::createFromFQCN('NopesOne'),
                    FullClassName::createFromFQCN('NopesTwo')
                ],
                [],
                $this->getMap(),
                [false, false]
            ]
       ];
    }
}