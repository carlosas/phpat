<?php

namespace Tests\PHPat\unit\Rule\Assertion\Inheritance;

use PHPat\Parser\Ast\FullClassName;
use PHPat\Rule\Assertion\Inheritance\MustNotExtend;
use Tests\PHPat\unit\Rule\Assertion\AbstractAssertionTestCase;

class MustNotExtendTest extends AbstractAssertionTestCase
{
    public function dataProvider(): array
    {
        return [
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('NotARealParent')],
                [],
                $this->getMap(),
                [true]
            ],
            //it fails because it extends Example\ParentClassExample
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('Example\ParentClassExample')],
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
