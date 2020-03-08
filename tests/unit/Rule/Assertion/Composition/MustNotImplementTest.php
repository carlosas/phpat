<?php

namespace Tests\PhpAT\unit\Rule\Assertion\Composition;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\AstNode;
use PhpAT\Parser\ClassLike;
use PhpAT\Parser\FullClassName;
use PhpAT\Parser\Relation\Composition;
use PhpAT\Parser\Relation\Dependency;
use PhpAT\Parser\Relation\Inheritance;
use PhpAT\Parser\Relation\Mixin;
use PhpAT\Rule\Assertion\Composition\MustNotImplement;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;
use PHPUnit\Framework\TestCase;

class MustNotImplementTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     * @param ClassLike   $origin
     * @param ClassLike[] $included
     * @param ClassLike[] $excluded
     * @param array       $astMap
     * @param bool[]      $expectedEvents
     */
    public function testDispatchesCorrectEvents(
        ClassLike $origin,
        array $included,
        array $excluded,
        array $astMap,
        array $expectedEvents
    ): void
    {
        $eventDispatcherMock = $this->createMock(EventDispatcher::class);
        $class = new MustNotImplement($eventDispatcherMock);

        foreach ($expectedEvents as $valid) {
            $eventType = $valid ? StatementValidEvent::class : StatementNotValidEvent::class;
            $consecutive[] = [$this->isInstanceOf($eventType)];
        }

        $eventDispatcherMock
            ->expects($this->exactly(count($consecutive??[])))
            ->method('dispatch')
            ->withConsecutive(...$consecutive??[]);

        $class->validate($origin, $included, $excluded, $astMap);
    }

    public function dataProvider(): array
    {
        return [
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('NotARealInterface')],
                [],
                $this->getAstMap(),
                [true]
            ],
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [
                    FullClassName::createFromFQCN('NopesOne'),
                    FullClassName::createFromFQCN('NopesTwo')
                ],
                [],
                $this->getAstMap(),
                [true, true]
            ],
            //it fails because Example\InterfaceExample is implemented
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [FullClassName::createFromFQCN('Example\InterfaceExample')],
                [],
                $this->getAstMap(),
                [false]
            ],
            //it fails twice because both are implemented
            [
                FullClassName::createFromFQCN('Example\ClassExample'),
                [
                    FullClassName::createFromFQCN('Example\InterfaceExample'),
                    FullClassName::createFromFQCN('Example\AnotherInterface'),
                ],
                [],
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