<?php
declare(strict_types=1);

namespace Tests\PhpAT\unit\Selector;
use PhpAT\App\Configuration;
use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\ClassLike;
use PhpAT\Parser\ComposerFileParser;
use PhpAT\Selector\ComposerDependencySelector;
use PHPUnit\Framework\TestCase;

class ComposerDependencySelectorTest extends TestCase
{
    public function testExtractsDependencies(): void
    {
        $selected = $this->select(false);

        $this->assertTrue($this->oneSelectedMatches($selected, 'Safe\\Foo'));
    }

    public function testDoesNotIncludeOwnNamespaces(): void
    {
        $selected = $this->select(false);
        $this->assertFalse($this->oneSelectedMatches($selected, 'Source\\Namespace\\Foo'));
    }

    /** @param ClassLike[] $selected */
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
     * @return ClassLike[]
     */
    private function select(bool $devMode): array
    {
        $selector = new ComposerDependencySelector('main', $devMode);
        $eventDispatcherMock = $this->createMock(EventDispatcher::class);
        $configurationMock = $this->createMock(Configuration::class);
        $configurationMock->method('getComposerConfiguration')->willReturn([
            'main' => [
                'json' => __DIR__.'/../Parser/Mock/fake-composer.json',
                'lock' => __DIR__.'/../Parser/Mock/fake-composer.lock'
            ]
        ]);
        $selector->injectDependencies([
            EventDispatcher::class => $eventDispatcherMock,
            Configuration::class => $configurationMock,
            ComposerFileParser::class => new ComposerFileParser()
        ]);

        return $selector->select();
    }
}
