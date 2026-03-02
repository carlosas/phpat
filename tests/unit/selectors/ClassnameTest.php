<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\Classname;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \PHPat\Selector\Classname
 */
class ClassnameTest extends TestCase
{
    public function testGetName(): void
    {
        $selector = new Classname('App\User', false);

        $this->assertEquals('App\User', $selector->getName());
    }

    public function testMatchesExactName(): void
    {
        $selector = new Classname('App\User', false);
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('getName')->willReturn('App\User');

        self::assertTrue($selector->matches($classReflection));
    }

    public function testMatchesExactNameWithLeadingBackslash(): void
    {
        $selector = new Classname('\App\User', false);
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('getName')->willReturn('App\User');

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchDifferentName(): void
    {
        $selector = new Classname('App\User', false);
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('getName')->willReturn('App\Admin');

        self::assertFalse($selector->matches($classReflection));
    }

    public function testMatchesRegex(): void
    {
        $selector = new Classname('/^App\\\/', true);
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('getName')->willReturn('App\User');

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchRegex(): void
    {
        $selector = new Classname('/^Vendor\\\/', true);
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('getName')->willReturn('App\User');

        self::assertFalse($selector->matches($classReflection));
    }
}
