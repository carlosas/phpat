<?php

namespace Tests\PHPat\unit\Rule\Assertion\Mixin;

use PHPat\Parser\Ast\FullClassName;
use PHPat\Rule\Assertion\Mixin\MustNotInclude;
use Tests\PHPat\unit\Rule\Assertion\AbstractAssertionTestCase;

class MustNotIncludeTest extends AbstractAssertionTestCase
{
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
    protected function getTestedClassName(): string
    {
        return MustNotInclude::class;
    }
}
