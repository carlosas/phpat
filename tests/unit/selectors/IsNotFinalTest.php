<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\IsNotFinal;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \PHPat\Selector\IsNotFinal
 */
class IsNotFinalTest extends TestCase
{
    public function testMatchesNonFinal(): void
    {
        $selector = new IsNotFinal();
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('isFinal')->willReturn(false);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchFinal(): void
    {
        $selector = new IsNotFinal();
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('isFinal')->willReturn(true);

        self::assertFalse($selector->matches($classReflection));
    }
}
