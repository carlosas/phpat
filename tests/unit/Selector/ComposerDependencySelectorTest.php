<?php
declare(strict_types=1);

namespace Tests\PhpAT\unit\Selector;

use PhpAT\Parser\ClassLike;
use PhpAT\Selector\ComposerDependencySelector;
use PHPUnit\Framework\TestCase;

class ComposerDependencySelectorTest extends TestCase
{
    public function testExtractsDependencies(): void
    {
        $selected = $this->select(false);
        $this->assertTrue($this->oneSelectedMatches($selected, 'Safe\\Foo'));
    }

    public function testIncludesOwnNamespaces(): void
    {
        $selected = $this->select(false);
        $this->assertTrue($this->oneSelectedMatches($selected, 'Source\\Namespace\\Foo'));
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

    /** @return ClassLike[] */
    private function select(bool $includeDev): array
    {
        return (new ComposerDependencySelector(
            __DIR__ . '/Mock/composer.json',
            __DIR__ . '/Mock/composer.lock',
            $includeDev
        ))->select();
    }
}
