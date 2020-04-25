<?php

namespace Tests\PhpAT\unit\Rule\Assertion\Mixin;

use PhpAT\Parser\FullClassName;
use PhpAT\Rule\Assertion\Mixin\CanOnlyInclude;
use Tests\PhpAT\unit\Rule\Assertion\AbstractAssertionTestCase;

class CanOnlyIncludeTest extends AbstractAssertionTestCase
{
    protected function getTestedClassName(): string
    {
        return CanOnlyInclude::class;
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
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [
                    FullClassName::createFromFQCN('Example\TraitExample'),
                    FullClassName::createFromFQCN('AnotherTrait')
                ],
                [],
                $this->getMap(),
                [true]
            ],
            //it fails because it includes Example\TraitExample
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('AnotherTrait')],
                [],
                $this->getMap(),
                [false]
            ],
        ];
    }
}