<?php

namespace Tests\PHPat\unit\Rule\Assertion\Inheritance;

use PHPat\Parser\Ast\FullClassName;
use PHPat\Rule\Assertion\Inheritance\MustExtend;
use Tests\PHPat\unit\Rule\Assertion\AbstractAssertionTestCase;

class MustExtendTest extends AbstractAssertionTestCase
{
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
    protected function getTestedClassName(): string
    {
        return MustExtend::class;
    }
}
