<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\IsTrait;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \PHPat\Selector\IsTrait
 */
class IsTraitTest extends TestCase
{
    public function testMatchesTrait(): void
    {
        $selector = new IsTrait();
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('isTrait')->willReturn(true);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchClass(): void
    {
        $selector = new IsTrait();
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('isTrait')->willReturn(false);

        self::assertFalse($selector->matches($classReflection));
    }
}
