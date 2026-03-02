<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\IsInterface;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \PHPat\Selector\IsInterface
 */
class IsInterfaceTest extends TestCase
{
    public function testMatchesInterface(): void
    {
        $selector = new IsInterface();
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('isInterface')->willReturn(true);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchClass(): void
    {
        $selector = new IsInterface();
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('isInterface')->willReturn(false);

        self::assertFalse($selector->matches($classReflection));
    }
}
