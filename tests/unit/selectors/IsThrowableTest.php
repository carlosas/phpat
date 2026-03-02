<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\IsThrowable;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \PHPat\Selector\IsThrowable
 */
class IsThrowableTest extends TestCase
{
    public function testMatchesThrowable(): void
    {
        $selector = new IsThrowable();
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('implementsInterface')->with(\Throwable::class)->willReturn(true);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchNonThrowable(): void
    {
        $selector = new IsThrowable();
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('implementsInterface')->with(\Throwable::class)->willReturn(false);

        self::assertFalse($selector->matches($classReflection));
    }
}
