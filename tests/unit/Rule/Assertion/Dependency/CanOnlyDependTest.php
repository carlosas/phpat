<?php

namespace Tests\PhpAT\unit\Rule\Assertion\Dependency;

use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Rule\Assertion\Dependency\CanOnlyDepend;
use Tests\PhpAT\unit\Rule\Assertion\AbstractAssertionTestCase;

class CanOnlyDependTest extends AbstractAssertionTestCase
{
    public function dataProvider(): array
    {
        return [
            [
                new FullClassName('Example\ClassExample'),
                [
                    new FullClassName('Example\AnotherClassExample'),
                    new FullClassName('Vendor\ThirdPartyExample')
                ],
                [],
                $this->getMap(),
                [true]
            ],
            [
                new FullClassName('Example\ClassExample'),
                [
                    new FullClassName('Example\AnotherClassExample'),
                    new FullClassName('Vendor\ThirdPartyExample'),
                    new FullClassName('ItDoesNotMatter')
                ],
                [],
                $this->getMap(),
                [true]
            ],
            //it fails because it also depends on Vendor\ThirdPartyExample
            [
                new FullClassName('Example\ClassExample'),
                [new FullClassName('Example\AnotherClassExample')],
                [],
                $this->getMap(),
                [false]],
            //it fails because there are 2 dependencies not listed
            [
                new FullClassName('Example\ClassExample'),
                [new FullClassName('NotARealClass')],
                [],
                $this->getMap(),
                [false,
                false]
            ],
        ];
    }
    protected function getTestedClassName(): string
    {
        return CanOnlyDepend::class;
    }
}
