<?php

namespace Tests\PHPat\unit\Rule\Assertion\Composition;

use PHPat\Parser\Ast\FullClassName;
use PHPat\Rule\Assertion\Composition\MustOnlyImplement;
use Tests\PHPat\unit\Rule\Assertion\AbstractAssertionTestCase;

class MustOnlyImplementTest extends AbstractAssertionTestCase
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
                [true, true, true]
            ],
            //it fails because it does not implement NotImplementedInterface
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [
                    FullClassName::createFromFQCN('Example\InterfaceExample'),
                    FullClassName::createFromFQCN('Example\AnotherInterface'),
                    FullClassName::createFromFQCN('NotImplementedInterface')
                ],
                [],
                $this->getMap(),
                [true, true, false, true]
            ],
            //it fails because Example\AnotherInterface is also implemented
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('Example\InterfaceExample')],
                [],
                $this->getMap(),
                [true, false]
            ],
            //it fails because it implements 2 that are not listed and does not implement NotARealInterface
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('NotARealInterface')],
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
