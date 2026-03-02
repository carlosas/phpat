<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\ClassNamespace;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \PHPat\Selector\ClassNamespace
 */
class ClassNamespaceTest extends TestCase
{
    public function testGetName(): void
    {
        $selector = new ClassNamespace('App\User', false);

        $this->assertEquals('App\User', $selector->getName());
    }

    public function testMatchesNamespace(): void
    {
        $selector = new ClassNamespace('App', false);
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('getName')->willReturn('App\User');

        self::assertTrue($selector->matches($classReflection));
    }

    public function testMatchesSubNamespace(): void
    {
        $selector = new ClassNamespace('App', false);
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('getName')->willReturn('App\Sub\User');

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchDifferentNamespace(): void
    {
        $selector = new ClassNamespace('App', false);
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('getName')->willReturn('Vendor\User');

        self::assertFalse($selector->matches($classReflection));
    }

    public function testMatchesRegex(): void
    {
        $selector = new ClassNamespace('/^App\\\Sub/', true);
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('getName')->willReturn('App\Sub\User');

        self::assertTrue($selector->matches($classReflection));
    }
}
