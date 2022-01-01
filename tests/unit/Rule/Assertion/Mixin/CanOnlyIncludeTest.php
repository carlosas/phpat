<?php

namespace Tests\PhpAT\unit\Rule\Assertion\Mixin;

use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Rule\Assertion\Mixin\CanOnlyInclude;
use Tests\PhpAT\unit\Rule\Assertion\AbstractAssertionTestCase;

class CanOnlyIncludeTest extends AbstractAssertionTestCase
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
            [
                new FullClassName('Example\ClassExample'),
                [
                    new FullClassName('Example\TraitExample'),
                    new FullClassName('AnotherTrait')
                ],
                [],
                $this->getMap(),
                [true]
            ],
            //it fails because it includes Example\TraitExample
            [
                new FullClassName('Example\ClassExample'),
                [new FullClassName('AnotherTrait')],
                [],
                $this->getMap(),
                [false]
            ],
        ];
    }
    protected function getTestedClassName(): string
    {
        return CanOnlyInclude::class;
    }
}
