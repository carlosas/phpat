<?php

namespace Tests\PHPat\unit\Rule\Assertion\Mixin;

use PHPat\Parser\Ast\FullClassName;
use PHPat\Rule\Assertion\Mixin\CanOnlyInclude;
use Tests\PHPat\unit\Rule\Assertion\AbstractAssertionTestCase;

class CanOnlyIncludeTest extends AbstractAssertionTestCase
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
    protected function getTestedClassName(): string
    {
        return CanOnlyInclude::class;
    }
}
