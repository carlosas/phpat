<?php
declare(strict_types=1);

namespace Tests\PhpAT\unit\Selector;

use PhpAT\App\Configuration;
use PHPAT\EventDispatcher\EventDispatcher;
use PhpAT\Parser\Ast\ClassLike;
use PhpAT\Parser\ComposerFileParser;
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

    /**
     * @param ClassLike[]  $selected
     * @param string $classToMatch
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
