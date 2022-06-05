<?php

namespace Tests\PHPat\unit\Rule\Assertion\Dependency;

use PHPat\Parser\Ast\FullClassName;
use PHPat\Rule\Assertion\Dependency\MustDepend;
use Tests\PHPat\unit\Rule\Assertion\AbstractAssertionTestCase;

class MustDependTest extends AbstractAssertionTestCase
{
    public function dataProvider(): array
    {
        return [
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('Example\AnotherClassExample')],
                [],
                $this->getMap(),
                [true]
            ],
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('Vendor\ThirdPartyExample')],
                [],
                $this->getMap(),
                [true]
            ],
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [
                    FullClassName::createFromFQCN('Example\AnotherClassExample'),
                    FullClassName::createFromFQCN('Vendor\ThirdPartyExample')
                ],
                [],
                $this->getMap(),
                [true, true]
            ],
            //it fails because it does not depend on NotARealClass
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('NotARealClass')],
                [],
                $this->getMap(),
                [false]
            ],
            //it fails because it does not depend on NotARealClass
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [
                    FullClassName::createFromFQCN('Example\AnotherClassExample'),
                    FullClassName::createFromFQCN('NotARealClass')
                ],
                [],
                $this->getMap(),
                [true, false]
            ],
            //it fails twice because it does not depend on any of both classes
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [
                    FullClassName::createFromFQCN('NopesOne'),
                    FullClassName::createFromFQCN('NopesTwo')
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
