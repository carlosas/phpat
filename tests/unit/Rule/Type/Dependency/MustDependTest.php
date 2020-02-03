<?php

namespace Tests\PhpAT\unit\Rule\Assertion\Dependency;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\AstNode;
use PhpAT\Parser\ClassName;
use PhpAT\Parser\Relation\Composition;
use PhpAT\Parser\Relation\Dependency;
use PhpAT\Parser\Relation\Inheritance;
use PhpAT\Parser\Relation\Mixin;
use PhpAT\Rule\Assertion\Dependency\MustDepend;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;
use PHPUnit\Framework\TestCase;

class MustDependTest extends TestCase
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
        $class = new MustDepend($eventDispatcherMock);

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
            ['Example\ClassExample', ['Example\AnotherClassExample'], $this->getAstMap(), false, [true]],
            ['Example\ClassExample', ['Vendor\ThirdPartyExample'], $this->getAstMap(), false, [true]],
            ['Example\ClassExample', ['NotARealClass'], $this->getAstMap(), true, [true]],
            //it fails because it depends on Example\AnotherClassExample
            ['Example\ClassExample', ['Example\AnotherClassExample'], $this->getAstMap(), true, [false]],
            //it fails because it does not depend on NotARealClass
            ['Example\ClassExample', ['NotARealClass'], $this->getAstMap(), false, [false]],
            //it fails twice because it does not depend on any of both classes
            ['Example\ClassExample', ['NopesOne', 'NopesTwo'], $this->getAstMap(), false, [false, false]],
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