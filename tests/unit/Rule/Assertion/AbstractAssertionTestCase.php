<?php

namespace Tests\PhpAT\unit\Rule\Assertion;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\Ast\AstNode;
use PhpAT\Parser\Ast\ReferenceMap;
use PhpAT\Parser\ClassLike;
use PhpAT\Parser\FullClassName;
use PhpAT\Parser\Relation\Composition;
use PhpAT\Parser\Relation\Dependency;
use PhpAT\Parser\Relation\Inheritance;
use PhpAT\Parser\Relation\Mixin;
use PhpAT\Rule\Assertion\AbstractAssertion;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

abstract class AbstractAssertionTestCase extends TestCase
{
    abstract public function dataProvider(): array;

    abstract protected function getTestedClassName(): string;

    /**
     * @dataProvider dataProvider
     * @param ClassLike    $origin The selected class in which to perform assertions
     * @param ClassLike[]  $included Classes that must be in the relation test
     * @param ClassLike[]  $excluded Classes excluded from the relation test
     * @param ReferenceMap $map The fake reference map
     * @param bool[]       $expectedEvents Expected ordered assertion results (true = valid , false = invalid)
     */
    public function testDispatchesCorrectEvents(
        ClassLike $origin,
        array $included,
        array $excluded,
        ReferenceMap $map,
        array $expectedEvents
    ): void
    {
        /** @var MockObject $eventDispatcherMock */
        $eventDispatcherMock = $this->createMock(EventDispatcher::class);
        $className = $this->getTestedClassName();
        /** @var AbstractAssertion $class */
        $class = new $className($eventDispatcherMock);

        foreach ($expectedEvents as $valid) {
            $eventType = $valid ? StatementValidEvent::class : StatementNotValidEvent::class;
            $consecutive[] = [$this->isInstanceOf($eventType)];
        }

        $eventDispatcherMock
            ->expects($this->exactly(count($consecutive??[])))
            ->method('dispatch')
            ->withConsecutive(...$consecutive??[]);

        $class->validate($origin, $included, $excluded, $map);
    }

    /**
     * Fake ReferenceMap for the tests
     */
    protected function getMap(): ReferenceMap
    {
        return new ReferenceMap(
            [
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
            ],
            []
        );
    }
}