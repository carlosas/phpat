<?php

namespace Tests\PhpAT\unit\Rule\Type\Composition;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\AstNode;
use PhpAT\Parser\ClassName;
use PhpAT\Rule\Type\Composition\MustImplement;
use PhpAT\Statement\Event\StatementNotValidEvent;
use PhpAT\Statement\Event\StatementValidEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MustImplementTest extends TestCase
{
    /** @var MustImplement */
    private $class;
    /** @var MockObject */
    private $eventDispatcherMock;

    public function setUp(): void
    {
        $this->eventDispatcherMock = $this->createMock(EventDispatcher::class);
        $this->class = new MustImplement($this->eventDispatcherMock);
    }

    /**
     * @dataProvider getSuccessCases
     * @param string $fqcnOrigin
     * @param array  $fqcnDestinations
     * @param array  $astMap
     * @param bool   $inverse
     */
    public function testDispatchesSuccess(
        string $fqcnOrigin,
        array $fqcnDestinations,
        array $astMap,
        bool $inverse = false
    ): void
    {
        $this->eventDispatcherMock
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(StatementValidEvent::class));

        $this->class->validate($fqcnOrigin, $fqcnDestinations, $astMap, $inverse);
    }

    /**
     * @dataProvider getFailureCases
     * @param string $fqcnOrigin
     * @param array  $fqcnDestinations
     * @param array  $astMap
     * @param bool   $inverse
     */
    public function testDispatchesFailure(
        string $fqcnOrigin,
        array $fqcnDestinations,
        array $astMap,
        bool $inverse = false
    ): void
    {
        $this->eventDispatcherMock
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(StatementNotValidEvent::class));

        $this->class->validate($fqcnOrigin, $fqcnDestinations, $astMap, $inverse);
    }

    public function getSuccessCases(): array
    {
        return [
            [ 'Example\ClassExample', ['Example\InterfaceExample'], $this->getAstMap(), false ],
            [ 'Example\ClassExample', ['NotARealInterface'], $this->getAstMap(), true ],
        ];
    }

    public function getFailureCases(): array
    {
        return [
            [ 'Example\ClassExample', ['Example\InterfaceExample'], $this->getAstMap(), true ],
            [ 'Example\ClassExample', ['NotARealInterface'], $this->getAstMap(), false ],
        ];
    }

    private function getAstMap(): array
    {
        return [
            new AstNode(
                new \SplFileInfo('folder/Example/ClassExample.php'),
                new ClassName('Example', 'ClassExample'),
                new ClassName('Example', 'ParentClassExample'),
                [
                    new ClassName('Example', 'AnotherClassExample'),
                    new ClassName('Vendor', 'ThirdPartyExample')
                ],
                [new ClassName('Example', 'InterfaceExample')],
                [new ClassName('Example', 'TraitExample')]
            )
        ];
    }
}