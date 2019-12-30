<?php

namespace Tests\PhpAT\unit\Rule\Type\Composition;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\AstNode;
use PhpAT\Parser\ClassName;
use PhpAT\Rule\Type\Composition\MustImplement;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;
use PHPUnit\Framework\TestCase;

class MustImplementTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     * @param int    $expectedSuccessEvents
     * @param int    $expectedFailureEvents
     * @param string $fqcnOrigin
     * @param array  $fqcnDestinations
     * @param array  $astMap
     * @param bool   $inverse
     */
    public function testDispatchesCorrectEvents(
        int $expectedSuccessEvents,
        int $expectedFailureEvents,
        string $fqcnOrigin,
        array $fqcnDestinations,
        array $astMap,
        bool $inverse = false
    ): void
    {
        $eventDispatcherMock = $this->createMock(EventDispatcher::class);
        $class = new MustImplement($eventDispatcherMock);

        for ($i=0; $i<$expectedSuccessEvents; $i++) {
            $consecutive[] = $this->isInstanceOf(StatementValidEvent::class);
        }
        for ($i=0; $i<$expectedFailureEvents; $i++) {
            $consecutive[] = $this->isInstanceOf(StatementNotValidEvent::class);
        }

        $eventDispatcherMock
            ->expects($this->exactly($expectedSuccessEvents + $expectedFailureEvents))
            ->method('dispatch')
            ->withConsecutive($consecutive??[]);

        $class->validate($fqcnOrigin, $fqcnDestinations, $astMap, $inverse);
    }

    public function dataProvider(): array
    {
        return [
            [1, 0, 'Example\ClassExample', ['Example\InterfaceExample'], $this->getAstMap(), false],
            [1, 0, 'Example\ClassExample', ['Example\InterfaceExample'], $this->getAstMap(), false],
            [1, 0, 'Example\ClassExample', ['NotARealInterface'], $this->getAstMap(), true],
            //it fails because Example\AnotherInterface is also implemented
            [0, 1, 'Example\ClassExample', ['Example\InterfaceExample'], $this->getAstMap(), true],
            //it fails because NotARealInterface is not implemented
            [0, 1, 'Example\ClassExample', ['NotARealInterface'], $this->getAstMap(), false],
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