<?php

namespace Tests\PHPat\unit\Rule\Assertion\Composition;

use PHPat\Parser\Ast\FullClassName;
use PHPat\Rule\Assertion\Composition\MustNotImplement;
use Tests\PHPat\unit\Rule\Assertion\AbstractAssertionTestCase;

class MustNotImplementTest extends AbstractAssertionTestCase
{
    public function dataProvider(): array
    {
        return [
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('NotARealInterface')],
                [],
                $this->getMap(),
                [true]
            ],
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [
                    FullClassName::createFromFQCN('NopesOne'),
                    FullClassName::createFromFQCN('NopesTwo')
                ],
                [],
                $this->getMap(),
                [true, true]
            ],
            //it fails because Example\InterfaceExample is implemented
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('Example\InterfaceExample')],
                [],
                $this->getMap(),
                [false]
            ],
            //it fails twice because both are implemented
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [
                    FullClassName::createFromFQCN('Example\InterfaceExample'),
                    FullClassName::createFromFQCN('Example\AnotherInterface'),
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
