<?php

namespace Tests\PHPat\unit\Rule\Assertion\Composition;

use PHPat\Parser\Ast\FullClassName;
use PHPat\Rule\Assertion\Composition\CanOnlyImplement;
use Tests\PHPat\unit\Rule\Assertion\AbstractAssertionTestCase;

class CanOnlyImplementTest extends AbstractAssertionTestCase
{
    public function dataProvider(): array
    {
        return [
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [
                    FullClassName::createFromFQCN('Example\InterfaceExample'),
                    FullClassName::createFromFQCN('Example\AnotherInterface')
                ],
                [],
                $this->getMap(),
                [true]
            ],
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [
                    FullClassName::createFromFQCN('Example\InterfaceExample'),
                    FullClassName::createFromFQCN('Example\AnotherInterface'),
                    FullClassName::createFromFQCN('NotImplementedInterface')
                ],
                [],
                $this->getMap(),
                [true]
            ],
            //it fails because Example\AnotherInterface is also implemented
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('Example\InterfaceExample')],
                [],
                $this->getMap(),
                [false]
            ],
            //it fails because there are 2 interface implementations not listed
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('NotARealInterface')],
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
