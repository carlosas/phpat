<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\AppliesAttribute;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \PHPat\Selector\AppliesAttribute
 */
class AppliesAttributeTest extends TestCase
{
    public function testMatchesAttribute(): void
    {
        $selector = new AppliesAttribute('App\MyAttribute', false);
        $classReflection = $this->createMock(\ReflectionClass::class);

        $attr = $this->createMock(\ReflectionAttribute::class);
        $attr->method('getName')->willReturn('App\MyAttribute');
        $attr->method('getArguments')->willReturn([]);

        $classReflection->method('getAttributes')->willReturn([$attr]);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchDifferentAttribute(): void
    {
        $selector = new AppliesAttribute('App\MyAttribute', false);
        $classReflection = $this->createMock(\ReflectionClass::class);

        $attr = $this->createMock(\ReflectionAttribute::class);
        $attr->method('getName')->willReturn('App\OtherAttribute');

        $classReflection->method('getAttributes')->willReturn([$attr]);

        self::assertFalse($selector->matches($classReflection));
    }

    public function testMatchesRegex(): void
    {
        $selector = new AppliesAttribute('/^App\\\/', true);
        $classReflection = $this->createMock(\ReflectionClass::class);

        $attr = $this->createMock(\ReflectionAttribute::class);
        $attr->method('getName')->willReturn('App\MyAttribute');
        $attr->method('getArguments')->willReturn([]);

        $classReflection->method('getAttributes')->willReturn([$attr]);

        self::assertTrue($selector->matches($classReflection));
    }
}
