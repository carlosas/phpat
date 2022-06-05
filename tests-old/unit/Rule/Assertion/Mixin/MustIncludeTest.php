<?php

namespace Tests\PHPat\unit\Rule\Assertion\Mixin;

use PHPat\Parser\Ast\FullClassName;
use PHPat\Rule\Assertion\Mixin\MustInclude;
use Tests\PHPat\unit\Rule\Assertion\AbstractAssertionTestCase;

class MustIncludeTest extends AbstractAssertionTestCase
{
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
    protected function getTestedClassName(): string
    {
        return MustInclude::class;
    }
}
