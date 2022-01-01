<?php

namespace Tests\PhpAT\unit\Rule\Assertion\Dependency;

use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Rule\Assertion\Dependency\MustDepend;
use Tests\PhpAT\unit\Rule\Assertion\AbstractAssertionTestCase;

class MustDependTest extends AbstractAssertionTestCase
{
    public function dataProvider(): array
    {
        return [
            [
                new FullClassName('Example\ClassExample'),
                [new FullClassName('Example\AnotherClassExample')],
                [],
                $this->getMap(),
                [true]
            ],
            [
                new FullClassName('Example\ClassExample'),
                [new FullClassName('Vendor\ThirdPartyExample')],
                [],
                $this->getMap(),
                [true]
            ],
            [
                new FullClassName('Example\ClassExample'),
                [
                    new FullClassName('Example\AnotherClassExample'),
                    new FullClassName('Vendor\ThirdPartyExample')
                ],
                [],
                $this->getMap(),
                [true, true]
            ],
            //it fails because it does not depend on NotARealClass
            [
                new FullClassName('Example\ClassExample'),
                [new FullClassName('NotARealClass')],
                [],
                $this->getMap(),
                [false]
            ],
            //it fails because it does not depend on NotARealClass
            [
                new FullClassName('Example\ClassExample'),
                [
                    new FullClassName('Example\AnotherClassExample'),
                    new FullClassName('NotARealClass')
                ],
                [],
                $this->getMap(),
                [true, false]
            ],
            //it fails twice because it does not depend on any of both classes
            [
                new FullClassName('Example\ClassExample'),
                [
                    new FullClassName('NopesOne'),
                    new FullClassName('NopesTwo')
                ],
                [],
                $this->getMap(),
                [false, false]
            ],
       ];
    }
    protected function getTestedClassName(): string
    {
        return MustDepend::class;
    }
}
