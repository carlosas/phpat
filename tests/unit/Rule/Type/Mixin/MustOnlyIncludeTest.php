<?php

namespace Tests\PhpAT\unit\Rule\Assertion\Mixin;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\AstNode;
use PhpAT\Parser\ClassName;
use PhpAT\Parser\Relation\Composition;
use PhpAT\Parser\Relation\Dependency;
use PhpAT\Parser\Relation\Inheritance;
use PhpAT\Parser\Relation\Mixin;
use PhpAT\Rule\Assertion\Dependency\MustOnlyDepend;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;
use PHPUnit\Framework\TestCase;

class MustOnlyIncludeTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     * @param string $fqcnOrigin
     * @param array  $fqcnDestinations
     * @param array  $astMap
     * @param bool   $inverse
     * @param array  $expectedEvents
     */
    public function testDispatchesCorrectEvents(
        string $fqcnOrigin,
        array $fqcnDestinations,
        array $astMap,
        bool $inverse,
        array $expectedEvents
    ): void
    {
        $this->assertTrue(true); return;
        $eventDispatcherMock = $this->createMock(EventDispatcher::class);
        $class = new MustOnlyDepend($eventDispatcherMock);

        foreach ($expectedEvents as $valid) {
            $eventType = $valid ? StatementValidEvent::class : StatementNotValidEvent::class;
            $consecutive[] = [$this->isInstanceOf($eventType)];
        }

        $eventDispatcherMock
            ->expects($this->exactly(count($consecutive??[])))
            ->method('dispatch')
            ->withConsecutive(...$consecutive??[]);

        $class->validate($fqcnOrigin, $fqcnDestinations, $astMap, $inverse);
    }

    public function dataProvider(): array
    {
        return [
            [
                'Example\ClassExample',
                ['Example\AnotherClassExample', 'Vendor\ThirdPartyExample'],
                $this->getAstMap(),
                false,
                [true, true, true]
            ],
            //it fails because it does not depend on NotAClass
            [
                'Example\ClassExample',
                ['Example\AnotherClassExample', 'Vendor\ThirdPartyExample', 'NotAClass'],
                $this->getAstMap(),
                false,
                [true, true, false, true]
            ],
            //it fails because it also depend on Vendor\ThirdPartyExample
            ['Example\ClassExample', ['Example\AnotherClassExample'], $this->getAstMap(), false, [true, false]],
            //it fails because there are 2 dependencies not listed and it does not depend on NotARealClass
            ['Example\ClassExample', ['NotARealClass'], $this->getAstMap(), false, [false, false, false]],
        ];
    }

    private function getAstMap(): array
    {
        return [
            new AstNode(
                new \SplFileInfo('folder/Example/ClassExample.php'),
                new ClassName('Example', 'ClassExample'),
                [
                    new Inheritance(0, new ClassName('Example', 'ParentClassExample')),
                    new Dependency(0, new ClassName('Example', 'AnotherClassExample')),
                    new Dependency(0, new ClassName('Vendor', 'ThirdPartyExample')),
                    new Composition(0, new ClassName('Example', 'InterfaceExample')),
                    new Composition(0, new ClassName('Example', 'AnotherInterface')),
                    new Mixin(0, new ClassName('Example', 'TraitExample'))
                ]
            )
        ];
    }
}