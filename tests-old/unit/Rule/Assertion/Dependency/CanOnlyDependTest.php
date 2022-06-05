<?php

namespace Tests\PHPat\unit\Rule\Assertion\Dependency;

use PHPat\Parser\Ast\FullClassName;
use PHPat\Rule\Assertion\Dependency\CanOnlyDepend;
use Tests\PHPat\unit\Rule\Assertion\AbstractAssertionTestCase;

class CanOnlyDependTest extends AbstractAssertionTestCase
{
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
    protected function getTestedClassName(): string
    {
        return CanOnlyDepend::class;
    }
}
