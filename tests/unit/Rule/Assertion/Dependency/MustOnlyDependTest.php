<?php

namespace Tests\PhpAT\unit\Rule\Assertion\Dependency;

use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Rule\Assertion\Dependency\MustOnlyDepend;
use Tests\PhpAT\unit\Rule\Assertion\AbstractAssertionTestCase;

class MustOnlyDependTest extends AbstractAssertionTestCase
{
    protected function getTestedClassName(): string
    {
        return MustOnlyDepend::class;
    }

    public function dataProvider(): array
    {
        return [
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [
                    FullClassName::createFromFQCN('Example\AnotherClassExample'),
                    FullClassName::createFromFQCN('Vendor\ThirdPartyExample')
                ],
                [],
                $this->getMap(),
                [true, true, true]
            ],
            //it fails because it does not depend on NotAClass
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [
                    FullClassName::createFromFQCN('Example\AnotherClassExample'),
                    FullClassName::createFromFQCN('Vendor\ThirdPartyExample'),
                    FullClassName::createFromFQCN('NotAClass')
                ],
                [],
                $this->getMap(),
                [true, true, false, true]
            ],
            //it fails because it also depend on Vendor\ThirdPartyExample
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [
                    FullClassName::createFromFQCN('Example\AnotherClassExample')
                ],
                [],
                $this->getMap(),
                [true, false]
            ],
            //it fails because there are 2 dependencies not listed and it does not depend on NotARealClass
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('NotARealClass')],
                [],
                $this->getMap(),
                [false, false, false]
            ],
        ];
    }
}