<?php

declare(strict_types=1);

namespace Tests\PhpAT\unit\Selector;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\Ast\ClassLike;
use PhpAT\Parser\Ast\ComposerPackage;
use PhpAT\Parser\Ast\FullClassName;
use PhpAT\Parser\Ast\ReferenceMap;
use PhpAT\Parser\Ast\RegexClassName;
use PhpAT\Parser\Ast\SrcNode;
use PhpAT\Selector\ComposerSourceSelector;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;

class ComposerSourceSelectorTest extends TestCase
{
    public function testExtractsSourceDirectories(): void
    {
        $source = $this->select(false);
        $this->assertTrue($this->oneSelectedMatches($source, 'Source\\Namespace\\Foo'));
    }

    public function testDoesNotExtractTestDirectoriesByDefault(): void
    {
        $source = $this->select(false);
        $this->assertFalse($this->oneSelectedMatches($source, 'Test\\Namespace\\Foo'));
    }

    public function testExtractsTestDirectoriesIfSpecified(): void
    {
        $source = $this->select(true);
        $this->assertTrue($this->oneSelectedMatches($source, 'Test\\Namespace\\Foo'));
    }

    /**
     * @param bool $devMode
     * @return array<ClassLike>
     */
    private function select(bool $devMode): array
    {
        $selector = new ComposerSourceSelector('main', $devMode);
        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);
        $selector->injectDependencies([EventDispatcherInterface::class => $eventDispatcherMock]);
        $referenceMapMock = $this->createMock(ReferenceMap::class);
        $srcNode = $this->createMock(SrcNode::class);
        $srcNode->method('getClassName')->willReturn('Source\Namespace\Foo');
        $referenceMapMock->method('getSrcNodes')->willReturn(['Source\Namespace\Foo' => $srcNode]);
        $referenceMapMock->method('getComposerPackages')->willReturn(
            [
                'main' => new ComposerPackage(
                    'main',
                    [new RegexClassName('Source\\Namespace\\*')],
                    [new RegexClassName('Test\\Namespace\\*')],
                    [new RegexClassName('Vendor\\*')],
                    [new RegexClassName('DevVendor\\*')]
                )
            ]
        );
        $selector->setReferenceMap($referenceMapMock);

        return $selector->select();
    }

    /**
     * @param array<ClassLike> $selected
     * @param string      $classToMatch
     * @return bool
     */
    private function oneSelectedMatches(array $selected, string $classToMatch): bool
    {
        foreach ($selected as $classLike) {
            if ($classLike->matches($classToMatch)) {
                return true;
            }
        }

        return false;
    }
}
