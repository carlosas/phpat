<?php

namespace Tests\PhpAT\unit\Rule\Assertion\Dependency;

use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Rule\Assertion\Dependency\MustNotDepend;
use Tests\PhpAT\unit\Rule\Assertion\AbstractAssertionTestCase;

class MustNotDependTest extends AbstractAssertionTestCase
{
    public function dataProvider(): array
    {
        return [
            [
                new FullClassName('Example\ClassExample'),
                [new FullClassName('NotARealClass')],
                [],
                $this->getMap(),
                [true]
            ],
            [
                new FullClassName('Example\ClassExample'),
                [
                    new FullClassName('NopesOne'),
                    new FullClassName('NopesTwo')
                ],
                [],
                $this->getMap(),
                [true, true]
            ],
            //it fails because it depends on Example\AnotherClassExample
            [
                new FullClassName('Example\ClassExample'),
                [new FullClassName('Example\AnotherClassExample')],
                [],
                $this->getMap(),
                [false]
            ],
            //it fails twice because it depends on two of them
            [
                new FullClassName('Example\ClassExample'),
                [
                    new FullClassName('Example\AnotherClassExample'),
                    new FullClassName('Nopes'),
                    new FullClassName('Vendor\ThirdPartyExample'),
                ],
                [],
                $this->getMap(),
                [false, true, false]
            ],
       ];
    }
    protected function getTestedClassName(): string
    {
        return MustNotDepend::class;
    }
}
