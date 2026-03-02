<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\IsException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \PHPat\Selector\IsException
 */
class IsExceptionTest extends TestCase
{
    public function testMatchesExceptionSubclass(): void
    {
        $selector = new IsException();
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('isSubclassOf')->with(\Exception::class)->willReturn(true);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchDifferentClass(): void
    {
        $selector = new IsException();
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('isSubclassOf')->with(\Exception::class)->willReturn(false);

        self::assertFalse($selector->matches($classReflection));
    }
}
