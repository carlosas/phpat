<?php

namespace Tests\PhpAT\unit\Rule\Assertion\Mixin;

use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Rule\Assertion\Mixin\MustInclude;
use Tests\PhpAT\unit\Rule\Assertion\AbstractAssertionTestCase;

class MustIncludeTest extends AbstractAssertionTestCase
{
    protected function getTestedClassName(): string
    {
        return MustInclude::class;
    }

    public function dataProvider(): array
    {
        return [
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('Example\TraitExample')],
                [],
                $this->getMap(),
                [true]
            ],
            //it fails because NotATrait is not included
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [
                    FullClassName::createFromFQCN('Example\TraitExample'),
                    FullClassName::createFromFQCN('NotATrait')
                ],
                [],
                $this->getMap(),
                [true, false]
            ],
            //it fails because NotATrait is not included
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('NotATrait')],
                [],
                $this->getMap(),
                [false]
            ],
            //it fails twice because any of them are included
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