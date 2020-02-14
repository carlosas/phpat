<?php

namespace Tests\PhpAT\unit\Rule\Assertion\Mixin;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\AstNode;
use PhpAT\Parser\ClassLike;
use PhpAT\Parser\FullClassName;
use PhpAT\Parser\Relation\Composition;
use PhpAT\Parser\Relation\Dependency;
use PhpAT\Parser\Relation\Inheritance;
use PhpAT\Parser\Relation\Mixin;
use PhpAT\Rule\Assertion\Mixin\MustInclude;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;
use PHPUnit\Framework\TestCase;

class MustIncludeTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     * @param ClassLike   $origin
     * @param ClassLike[] $destinations
     * @param array       $astMap
     * @param array  $expectedEvents
     */
    public function testDispatchesCorrectEvents(
        ClassLike $origin,
        array $destinations,
        array $astMap,
        array $expectedEvents
    ): void
    {
        $eventDispatcherMock = $this->createMock(EventDispatcher::class);
        $class = new MustInclude($eventDispatcherMock);

        foreach ($expectedEvents as $valid) {
            $eventType = $valid ? StatementValidEvent::class : StatementNotValidEvent::class;
            $consecutive[] = [$this->isInstanceOf($eventType)];
        }

        $eventDispatcherMock
            ->expects($this->exactly(count($consecutive??[])))
            ->method('dispatch')
            ->withConsecutive(...$consecutive??[]);

        $class->validate($origin, $destinations, $astMap);
    }

    public function dataProvider(): array
    {
        return [
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('Example\TraitExample')],
                $this->getAstMap(),
                [true]
            ],
            //it fails because NotATrait is not included
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [
                    FullClassName::createFromFQCN('Example\TraitExample'),
                    FullClassName::createFromFQCN('NotATrait')
                ],
                $this->getAstMap(),
                [true, false]
            ],
            //it fails because NotATrait is not included
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('NotATrait')],
                $this->getAstMap(),
                [false]
            ],
            //it fails twice because any of them are included
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [
                    FullClassName::createFromFQCN('NopesOne'),
                    FullClassName::createFromFQCN('NopesTwo')
                ],
                $this->getAstMap(),
                [false, false]
            ]
       ];
    }

    private function getAstMap(): array
    {
        return [
            new AstNode(
                new \SplFileInfo('folder/Example/ClassExample.php'),
                new FullClassName('Example', 'ClassExample'),
                [
                    new Inheritance(0, new FullClassName('Example', 'ParentClassExample')),
                    new Dependency(0, new FullClassName('Example', 'AnotherClassExample')),
                    new Dependency(0, new FullClassName('Vendor', 'ThirdPartyExample')),
                    new Composition(0, new FullClassName('Example', 'InterfaceExample')),
                    new Composition(0, new FullClassName('Example', 'AnotherInterface')),
                    new Mixin(0, new FullClassName('Example', 'TraitExample'))
                ]
            )
       ];
    }
}