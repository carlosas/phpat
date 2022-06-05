<?php

namespace Tests\PHPat\unit\Rule\Assertion;

use PHPat\App\Configuration;
use PHPat\Parser\Ast\ClassLike;
use PHPat\Parser\Ast\ComposerPackage;
use PHPat\Parser\Ast\FullClassName;
use PHPat\Parser\Ast\ReferenceMap;
use PHPat\Parser\Ast\SrcNode;
use PHPat\Parser\Relation\Composition;
use PHPat\Parser\Relation\Dependency;
use PHPat\Parser\Relation\Inheritance;
use PHPat\Parser\Relation\Mixin;
use PHPat\Rule\Assertion\AbstractAssertion;
use PHPat\Statement\Event\StatementNotValidEvent;
use PHPat\Statement\Event\StatementValidEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;

abstract class AbstractAssertionTestCase extends TestCase
{
    abstract public function dataProvider(): array;

    /**
     * @dataProvider dataProvider
     * @param ClassLike    $origin The selected class in which to perform assertions
     * @param array<ClassLike> $included Classes that must be in the relation test
     * @param array<ClassLike> $excluded Classes excluded from the relation test
     * @param ReferenceMap $map The fake reference map
     * @param array<bool>       $expectedEvents Expected ordered assertion results (true = valid , false = invalid)
     */
    public function testDispatchesCorrectEvents(
        ClassLike $origin,
        array $included,
        array $excluded,
        ReferenceMap $map,
        array $expectedEvents
    ): void {
        /** @var EventDispatcherInterface|MockObject $eventDispatcherMock */
        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);
        /** @var Configuration|MockObject $configurationMock */
        $configurationMock = $this->createMock(Configuration::class);
        $configurationMock->method('getIgnorePhpExtensions')->willReturn(true);
        $className = $this->getTestedClassName();
        /** @var AbstractAssertion $class */
        $class = new $className($eventDispatcherMock, $configurationMock);

        foreach ($expectedEvents as $valid) {
            $eventType     = $valid ? StatementValidEvent::class : StatementNotValidEvent::class;
            $consecutive[] = [$this->isInstanceOf($eventType)];
        }

        $eventDispatcherMock
            ->expects($this->exactly(count($consecutive ?? [])))
            ->method('dispatch')
            ->withConsecutive(...$consecutive ?? []);

        $class->validate($origin, $included, $excluded, $map);
    }

    abstract protected function getTestedClassName(): string;

    /**
     * Fake ReferenceMap for the tests
     */
    protected function getMap(): ReferenceMap
    {
        return new ReferenceMap(
            [
                'Example\\ClassExample' => new SrcNode(
                    'folder/Example/ClassExample.php',
                    FullClassName::createFromFQCN('Example\\ClassExample'),
                    [
                        new Inheritance(FullClassName::createFromFQCN('Example\\ParentClassExample'), 0, 0),
                        new Inheritance(FullClassName::createFromFQCN('\\FilterIterator'), 0, 0),
                        new Dependency(FullClassName::createFromFQCN('Example\\AnotherClassExample'), 0, 0),
                        new Dependency(FullClassName::createFromFQCN('Vendor\\ThirdPartyExample'), 0, 0),
                        new Dependency(FullClassName::createFromFQCN('iterable'), 0, 0),
                        new Composition(FullClassName::createFromFQCN('Example\\InterfaceExample'), 0, 0),
                        new Composition(FullClassName::createFromFQCN('Example\\AnotherInterface'), 0, 0),
                        new Composition(FullClassName::createFromFQCN('iterable'), 0, 0),
                        new Mixin(FullClassName::createFromFQCN('Example\\TraitExample'), 0, 0),
                        new Mixin(FullClassName::createFromFQCN('PHPDocElement'), 0, 0)
                    ]
                )
            ],
            [
                FullClassName::createFromFQCN('iterable'),
                FullClassName::createFromFQCN('\\FilterIterator'),
                FullClassName::createFromFQCN('PHPDocElement'),
            ],
            [
                new ComposerPackage('main', [], [], [], [])
            ]
        );
    }
}
