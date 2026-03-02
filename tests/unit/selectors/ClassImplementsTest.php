<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\ClassImplements;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \PHPat\Selector\ClassImplements
 */
class ClassImplementsTest extends TestCase
{
    public function testMatchesInterface(): void
    {
        $selector = new ClassImplements('App\MyInterface', false);
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('implementsInterface')->with('App\MyInterface')->willReturn(true);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchInterface(): void
    {
        $selector = new ClassImplements('App\MyInterface', false);
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('implementsInterface')->with('App\MyInterface')->willReturn(false);

        self::assertFalse($selector->matches($classReflection));
    }

    public function testMatchesRegex(): void
    {
        $selector = new ClassImplements('/^App\\\/', true);
        $classReflection = $this->createMock(\ReflectionClass::class);

        $iface = $this->createMock(\ReflectionClass::class);
        $iface->method('getName')->willReturn('App\MyInterface');

        $classReflection->method('getInterfaces')->willReturn(['App\MyInterface' => $iface]);

        self::assertTrue($selector->matches($classReflection));
    }
}
