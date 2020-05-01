<?php

namespace Tests\PhpAT\unit\Rule\Assertion\Mixin;

use PhpAT\Parser\FullClassName;
use PhpAT\Rule\Assertion\Mixin\MustNotInclude;
use Tests\PhpAT\unit\Rule\Assertion\AbstractAssertionTestCase;

class MustNotIncludeTest extends AbstractAssertionTestCase
{
    protected function getTestedClassName(): string
    {
        return MustNotInclude::class;
    }

    public function dataProvider(): array
    {
        return [
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
            //it fails because Example\TraitExample is included
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [
                    FullClassName::createFromFQCN('NotATrait'),
                    FullClassName::createFromFQCN('Example\TraitExample')
                ],
                [],
                $this->getMap(),
                [true, false]
            ],
            //it fails because Example\TraitExample is included
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('Example\TraitExample')],
                [],
                $this->getMap(),
                [false]
            ]
       ];
    }
}