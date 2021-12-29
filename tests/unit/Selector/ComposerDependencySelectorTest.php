<?php

declare(strict_types=1);

namespace Tests\PhpAT\unit\Selector;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\Ast\ClassLike;
use PhpAT\Parser\Ast\ComposerPackage;
use PhpAT\Parser\Ast\ReferenceMap;
use PhpAT\Parser\Ast\RegexClassName;
use PhpAT\Selector\ComposerDependencySelector;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;

class ComposerDependencySelectorTest extends TestCase
{
    public function testExtractsDependencies(): void
    {
        $selected = $this->select(false);

        $this->assertTrue($this->oneSelectedMatches($selected, 'Vendor\\Foo'));
    }

    public function testDoesNotIncludeOwnNamespaces(): void
    {
        $selected = $this->select(false);
        $this->assertFalse($this->oneSelectedMatches($selected, 'Source\\Namespace\\Foo'));
    }

    public function testExtractsDevDependencies(): void
    {
        $selected = $this->select(true);

        $this->assertTrue($this->oneSelectedMatches($selected, 'DevVendor\\Foo'));
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

    /**
     * @param bool $devMode
     * @return array<ClassLike>
     */
    private function select(bool $devMode): array
    {
        $selector = new ComposerDependencySelector('main', $devMode);
        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);
        $selector->injectDependencies([EventDispatcherInterface::class => $eventDispatcherMock]);
        $referenceMapMock = $this->createMock(ReferenceMap::class);
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
}
