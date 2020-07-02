<?php
declare(strict_types=1);

namespace Tests\PhpAT\unit\Selector;

use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\Ast\ClassLike;
use PhpAT\Parser\Ast\ComposerPackage;
use PhpAT\Parser\Ast\ReferenceMap;
use PhpAT\Parser\Ast\RegexClassName;
use PhpAT\Selector\ComposerSourceSelector;
use PHPUnit\Framework\TestCase;

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
     * @return ClassLike[]
     */
    private function select(bool $devMode): array
    {
        $selector = new ComposerSourceSelector('main', $devMode);
        $eventDispatcherMock = $this->createMock(EventDispatcher::class);
        $selector->injectDependencies([EventDispatcher::class => $eventDispatcherMock]);
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

    /**
     * @param ClassLike[] $selected
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
