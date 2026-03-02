<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\IsError;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \PHPat\Selector\IsError
 */
class IsErrorTest extends TestCase
{
    public function testMatchesErrorSubclass(): void
    {
        $selector = new IsError();
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('isSubclassOf')->with(\Error::class)->willReturn(true);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchDifferentClass(): void
    {
        $selector = new IsError();
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('isSubclassOf')->with(\Error::class)->willReturn(false);

        self::assertFalse($selector->matches($classReflection));
    }
}
