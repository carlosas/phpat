<?php

namespace Tests\PhpAT\unit\Rule\Assertion\Dependency;

use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Rule\Assertion\Dependency\CanOnlyDepend;
use Tests\PhpAT\unit\Rule\Assertion\AbstractAssertionTestCase;

class CanOnlyDependTest extends AbstractAssertionTestCase
{
    protected function getTestedClassName(): string
    {
        return CanOnlyDepend::class;
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
                [true]
            ],
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [
                    FullClassName::createFromFQCN('Example\AnotherClassExample'),
                    FullClassName::createFromFQCN('Vendor\ThirdPartyExample'),
                    FullClassName::createFromFQCN('ItDoesNotMatter')
                ],
                [],
                $this->getMap(),
                [true]
            ],
            //it fails because it also depends on Vendor\ThirdPartyExample
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('Example\AnotherClassExample')],
                [],
                $this->getMap(),
                [false]],
            //it fails because there are 2 dependencies not listed
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('NotARealClass')],
                [],
                $this->getMap(),
                [false,
                false]
            ],
        ];
    }
}
