<?php

namespace Tests\PHPat\unit\Rule\Assertion\Mixin;

use PHPat\Parser\Ast\FullClassName;
use PHPat\Rule\Assertion\Mixin\MustOnlyInclude;
use Tests\PHPat\unit\Rule\Assertion\AbstractAssertionTestCase;

class MustOnlyIncludeTest extends AbstractAssertionTestCase
{
    public function dataProvider(): array
    {
        return [
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [
                    FullClassName::createFromFQCN('Example\TraitExample')
                ],
                [],
                $this->getMap(),
                [true, true]
            ],
            //it fails because it does not include on NotAClass
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [
                    FullClassName::createFromFQCN('Example\TraitExample'),
                    FullClassName::createFromFQCN('NotAClass')
                ],
                [],
                $this->getMap(),
                [true, false, true]
            ],
            //it fails because it includes a trait not listed and it does not include NotARealClass
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('NotARealClass')],
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
