<?php

namespace Tests\PhpAT\unit\Rule\Assertion\Dependency;

use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Rule\Assertion\Dependency\MustOnlyDepend;
use Tests\PhpAT\unit\Rule\Assertion\AbstractAssertionTestCase;

class MustOnlyDependTest extends AbstractAssertionTestCase
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
                [true, true, true]
            ],
            //it fails because it does not depend on NotAClass
            [
                new FullClassName('Example\ClassExample'),
                [
                    new FullClassName('Example\AnotherClassExample'),
                    new FullClassName('Vendor\ThirdPartyExample'),
                    new FullClassName('NotAClass')
                ],
                [],
                $this->getMap(),
                [true, true, false, true]
            ],
            //it fails because it also depend on Vendor\ThirdPartyExample
            [
                new FullClassName('Example\ClassExample'),
                [
                    new FullClassName('Example\AnotherClassExample')
                ],
                [],
                $this->getMap(),
                [true, false]
            ],
            //it fails because there are 2 dependencies not listed and it does not depend on NotARealClass
            [
                new FullClassName('Example\ClassExample'),
                [new FullClassName('NotARealClass')],
                [],
                $this->getMap(),
                [false, false, false]
            ],
        ];
    }
    protected function getTestedClassName(): string
    {
        return MustOnlyDepend::class;
    }
}
