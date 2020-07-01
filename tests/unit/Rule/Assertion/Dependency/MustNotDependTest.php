<?php

namespace Tests\PhpAT\unit\Rule\Assertion\Dependency;

use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Rule\Assertion\Dependency\MustNotDepend;
use Tests\PhpAT\unit\Rule\Assertion\AbstractAssertionTestCase;

class MustNotDependTest extends AbstractAssertionTestCase
{
    protected function getTestedClassName(): string
    {
        return MustNotDepend::class;
    }

    public function dataProvider(): array
    {
        return [
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('NotARealClass')],
                [],
                $this->getMap(),
                [true]
            ],
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
            //it fails because it depends on Example\AnotherClassExample
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('Example\AnotherClassExample')],
                [],
                $this->getMap(),
                [false]
            ],
            //it fails twice because it depends on two of them
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [
                    FullClassName::createFromFQCN('Example\AnotherClassExample'),
                    FullClassName::createFromFQCN('Nopes'),
                    FullClassName::createFromFQCN('Vendor\ThirdPartyExample'),
                ],
                [],
                $this->getMap(),
                [false, true, false]
            ],
       ];
    }
}