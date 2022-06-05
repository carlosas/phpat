<?php

namespace Tests\PHPat\unit\Rule\Assertion\Inheritance;

use PHPat\Parser\Ast\FullClassName;
use PHPat\Rule\Assertion\Inheritance\CanOnlyExtend;
use Tests\PHPat\unit\Rule\Assertion\AbstractAssertionTestCase;

class CanOnlyExtendTest extends AbstractAssertionTestCase
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
            //it fails because it does not extend Example\AnotherClass
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('Example\AnotherClass')],
                [],
                $this->getMap(),
                [false]
            ],
            //it fails because it extends from a class not listed
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('NotARealParent')],
                [],
                $this->getMap(),
                [false]
            ],
        ];
    }
    protected function getTestedClassName(): string
    {
        return CanOnlyExtend::class;
    }
}
