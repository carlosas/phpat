<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\IsFinal;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \PHPat\Selector\IsFinal
 */
class IsFinalTest extends TestCase
{
    public function testMatchesFinal(): void
    {
        $selector = new IsFinal();
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('isFinal')->willReturn(true);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchNonFinal(): void
    {
        $selector = new IsFinal();
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('isFinal')->willReturn(false);

        self::assertFalse($selector->matches($classReflection));
    }
}
