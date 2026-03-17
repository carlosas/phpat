<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\Filepath;

/**
 * @internal
 *
 * @covers \PHPat\Selector\Filepath
 */
class FilepathTest extends SelectorTestCase
{
    public function testGetName(): void
    {
        $selector = new Filepath('src/User.php', false);

        $this->assertEquals('src/User.php', $selector->getName());
    }

    public function testMatchesFilepath(): void
    {
        $path = realpath(__DIR__.'/Fixtures.php');
        $selector = new Filepath($path, false);
        $classReflection = $this->getReflectionClass(DummyClassValid::class);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchDifferentFilepath(): void
    {
        $selector = new Filepath('src/Admin.php', false);
        $classReflection = $this->getReflectionClass(DummyClassValid::class);

        self::assertFalse($selector->matches($classReflection));
    }

    public function testHandlesFalseFilepath(): void
    {
        // Internal classes like stdClass don't have a filepath (getFileName returns null)
        $selector = new Filepath('src/User.php', false);
        $classReflection = $this->getReflectionClass(\stdClass::class);

        self::assertFalse($selector->matches($classReflection));
    }

    public function testMatchesRegex(): void
    {
        $selector = new Filepath('/Fixtures\.php$/', true);
        $classReflection = $this->getReflectionClass(DummyClassValid::class);

        self::assertTrue($selector->matches($classReflection));
    }
}
