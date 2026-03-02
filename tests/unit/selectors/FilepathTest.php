<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\Filepath;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \PHPat\Selector\Filepath
 */
class FilepathTest extends TestCase
{
    public function testGetName(): void
    {
        $selector = new Filepath('src/User.php', false);

        $this->assertEquals('src/User.php', $selector->getName());
    }

    public function testMatchesFilepath(): void
    {
        $selector = new Filepath('src/User.php', false);
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('getFileName')->willReturn('src/User.php');

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchDifferentFilepath(): void
    {
        $selector = new Filepath('src/User.php', false);
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('getFileName')->willReturn('src/Admin.php');

        self::assertFalse($selector->matches($classReflection));
    }

    public function testHandlesFalseFilepath(): void
    {
        $selector = new Filepath('src/User.php', false);
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('getFileName')->willReturn(false);

        self::assertFalse($selector->matches($classReflection));
    }

    public function testMatchesRegex(): void
    {
        $selector = new Filepath('/\.php$/', true);
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('getFileName')->willReturn('src/User.php');

        self::assertTrue($selector->matches($classReflection));
    }
}
