<?php

namespace Tests\PhpAT\unit\Rule\Assertion\Inheritance;

use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Rule\Assertion\Inheritance\MustNotExtend;
use Tests\PhpAT\unit\Rule\Assertion\AbstractAssertionTestCase;

class MustNotExtendTest extends AbstractAssertionTestCase
{
    public function dataProvider(): array
    {
        return [
            [
                new FullClassName('Example\ClassExample'),
                [new FullClassName('NotARealParent')],
                [],
                $this->getMap(),
                [true]
            ],
            //it fails because it extends Example\ParentClassExample
            [
                new FullClassName('Example\ClassExample'),
                [new FullClassName('Example\ParentClassExample')],
                [],
                $this->getMap(),
                [false]
            ]
       ];
    }
    protected function getTestedClassName(): string
    {
        return MustNotExtend::class;
    }
}
