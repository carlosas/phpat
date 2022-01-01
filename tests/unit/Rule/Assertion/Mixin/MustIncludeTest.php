<?php

namespace Tests\PhpAT\unit\Rule\Assertion\Mixin;

use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Rule\Assertion\Mixin\MustInclude;
use Tests\PhpAT\unit\Rule\Assertion\AbstractAssertionTestCase;

class MustIncludeTest extends AbstractAssertionTestCase
{
    public function dataProvider(): array
    {
        return [
            [
                new FullClassName('Example\ClassExample'),
                [new FullClassName('Example\TraitExample')],
                [],
                $this->getMap(),
                [true]
            ],
            //it fails because NotATrait is not included
            [
                new FullClassName('Example\ClassExample'),
                [
                    new FullClassName('Example\TraitExample'),
                    new FullClassName('NotATrait')
                ],
                [],
                $this->getMap(),
                [true, false]
            ],
            //it fails because NotATrait is not included
            [
                new FullClassName('Example\ClassExample'),
                [new FullClassName('NotATrait')],
                [],
                $this->getMap(),
                [false]
            ],
            //it fails twice because any of them are included
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
        return MustInclude::class;
    }
}
