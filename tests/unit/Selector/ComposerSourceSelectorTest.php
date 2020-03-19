<?php
declare(strict_types=1);

namespace Tests\PhpAT\unit\Selector;

use PhpAT\Parser\ClassLike;
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

    /** @return ClassLike[] $selected */
    private function select(bool $includeDev): array
    {
        return (new ComposerSourceSelector(__DIR__ . '/Mock/composer.json', $includeDev))->select();
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
}
