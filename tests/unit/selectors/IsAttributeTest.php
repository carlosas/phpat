<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\IsAttribute;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \PHPat\Selector\IsAttribute
 */
class IsAttributeTest extends TestCase
{
    public function testMatchesAttribute(): void
    {
        $selector = new IsAttribute();
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('getAttributes')->with(\Attribute::class)->willReturn([$this->createMock(\ReflectionAttribute::class)]);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchNonAttribute(): void
    {
        $selector = new IsAttribute();
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('getAttributes')->with(\Attribute::class)->willReturn([]);

        self::assertFalse($selector->matches($classReflection));
    }
}
