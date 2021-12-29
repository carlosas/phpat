<?php

namespace Tests\PhpAT\unit\Rule\Assertion\Inheritance;

use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Rule\Assertion\Inheritance\MustExtend;
use Tests\PhpAT\unit\Rule\Assertion\AbstractAssertionTestCase;

class MustExtendTest extends AbstractAssertionTestCase
{
    protected function getTestedClassName(): string
    {
        return MustExtend::class;
    }

    public function dataProvider(): array
    {
        return [
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('Example\ParentClassExample')],
                [],
                $this->getMap(),
                [true]
            ],
            //it fails because it does not extends NotARealParent
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('NotARealParent')],
                [],
                $this->getMap(),
                [false]
            ]
       ];
    }
}
