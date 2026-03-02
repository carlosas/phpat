<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\IsAbstract;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \PHPat\Selector\IsAbstract
 */
class IsAbstractTest extends TestCase
{
    public function testMatchesAbstract(): void
    {
        $selector = new IsAbstract();
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('isAbstract')->willReturn(true);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchConcrete(): void
    {
        $selector = new IsAbstract();
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('isAbstract')->willReturn(false);

        self::assertFalse($selector->matches($classReflection));
    }
}
