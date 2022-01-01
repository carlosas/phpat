<?php

namespace Tests\PhpAT\unit\Rule\Assertion\Mixin;

use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Rule\Assertion\Mixin\MustOnlyInclude;
use Tests\PhpAT\unit\Rule\Assertion\AbstractAssertionTestCase;

class MustOnlyIncludeTest extends AbstractAssertionTestCase
{
    public function dataProvider(): array
    {
        return [
            [
                new FullClassName('Example\ClassExample'),
                [
                    new FullClassName('Example\TraitExample')
                ],
                [],
                $this->getMap(),
                [true, true]
            ],
            //it fails because it does not include on NotAClass
            [
                new FullClassName('Example\ClassExample'),
                [
                    new FullClassName('Example\TraitExample'),
                    new FullClassName('NotAClass')
                ],
                [],
                $this->getMap(),
                [true, false, true]
            ],
            //it fails because it includes a trait not listed and it does not include NotARealClass
            [
                new FullClassName('Example\ClassExample'),
                [new FullClassName('NotARealClass')],
                [],
                $this->getMap(),
                [false, false]
            ],
        ];
    }
    protected function getTestedClassName(): string
    {
        return MustOnlyInclude::class;
    }
}
