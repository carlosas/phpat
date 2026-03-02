<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\IsReadonly;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \PHPat\Selector\IsReadonly
 */
class IsReadonlyTest extends TestCase
{
    public function testMatchesReadonly(): void
    {
        $selector = new IsReadonly();
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('isReadOnly')->willReturn(true);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchNonReadonly(): void
    {
        $selector = new IsReadonly();
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('isReadOnly')->willReturn(false);

        self::assertFalse($selector->matches($classReflection));
    }
}
