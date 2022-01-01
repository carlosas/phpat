<?php

namespace Tests\PhpAT\unit\Rule\Assertion\Inheritance;

use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Rule\Assertion\Inheritance\MustExtend;
use Tests\PhpAT\unit\Rule\Assertion\AbstractAssertionTestCase;

class MustExtendTest extends AbstractAssertionTestCase
{
    public function dataProvider(): array
    {
        return [
            [
                new FullClassName('Example\ClassExample'),
                [new FullClassName('Example\ParentClassExample')],
                [],
                $this->getMap(),
                [true]
            ],
            //it fails because it does not extends NotARealParent
            [
                new FullClassName('Example\ClassExample'),
                [new FullClassName('NotARealParent')],
                [],
                $this->getMap(),
                [false]
            ]
       ];
    }
    protected function getTestedClassName(): string
    {
        return MustExtend::class;
    }
}
