<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\IsEnum;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \PHPat\Selector\IsEnum
 */
class IsEnumTest extends TestCase
{
    public function testMatchesEnum(): void
    {
        $selector = new IsEnum();
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('isEnum')->willReturn(true);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchNonEnum(): void
    {
        $selector = new IsEnum();
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('isEnum')->willReturn(false);

        self::assertFalse($selector->matches($classReflection));
    }
}
