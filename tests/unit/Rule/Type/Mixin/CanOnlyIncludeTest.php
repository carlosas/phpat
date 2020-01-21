<?php

namespace Tests\PhpAT\unit\Rule\Assertion\Mixin;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\AstNode;
use PhpAT\Parser\ClassName;
use PhpAT\Rule\Assertion\Mixin\CanOnlyInclude;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;
use PHPUnit\Framework\TestCase;

class CanOnlyIncludeTest extends TestCase
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
        $class = new CanOnlyInclude($eventDispatcherMock);

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
            ['Example\ClassExample', ['Example\TraitExample'], $this->getAstMap(), false, [true]],
            ['Example\ClassExample', ['Example\TraitExample', 'AnotherTrait'], $this->getAstMap(), false, [true]],
            //it fails because it includes Example\TraitExample
            ['Example\ClassExample', ['AnotherTrait'], $this->getAstMap(), false, [false]],
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