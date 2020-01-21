<?php

namespace Tests\PhpAT\unit\Rule\Assertion\Dependency;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\AstNode;
use PhpAT\Parser\ClassName;
use PhpAT\Rule\Assertion\Dependency\CanOnlyDepend;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;
use PHPUnit\Framework\TestCase;

class CanOnlyDependTest extends TestCase
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
        $eventDispatcherMock = $this->createMock(EventDispatcher::class);
        $class = new CanOnlyDepend($eventDispatcherMock);

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
                [true]
            ],
            [
                'Example\ClassExample',
                ['Example\AnotherClassExample', 'Vendor\ThirdPartyExample', 'ItDoesNotMatter'],
                $this->getAstMap(),
                false,
                [true]
            ],
            //it fails because it also depends on Vendor\ThirdPartyExample
            ['Example\ClassExample', ['Example\AnotherClassExample'], $this->getAstMap(), false, [false]],
            //it fails because there are 2 dependencies not listed
            ['Example\ClassExample', ['NotARealClass'], $this->getAstMap(), false, [false, false]],
        ];
    }

    private function getAstMap(): array
    {
        return [
            new AstNode(
                new \SplFileInfo('folder/Example/ClassExample.php'), //File
                new ClassName('Example', 'ClassExample'), //Classname
                new ClassName('Example', 'ParentClassExample'), //Parent
                [
                    new ClassName('Example', 'AnotherClassExample'), //Dependency
                    new ClassName('Vendor', 'ThirdPartyExample') //Dependency
                ],
                [
                    new ClassName('Example', 'InterfaceExample'), //Interface
                    new ClassName('Example', 'AnotherInterface') //Interface
                ],
                [new ClassName('Example', 'TraitExample')] //Trait
            )
        ];
    }
}