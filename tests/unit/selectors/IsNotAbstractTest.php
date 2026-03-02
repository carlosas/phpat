<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\IsNotAbstract;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \PHPat\Selector\IsNotAbstract
 */
class IsNotAbstractTest extends TestCase
{
    public function testMatchesConcrete(): void
    {
        $selector = new IsNotAbstract();
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('isAbstract')->willReturn(false);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchAbstract(): void
    {
        $selector = new IsNotAbstract();
        $classReflection = $this->createMock(\ReflectionClass::class);
        $classReflection->method('isAbstract')->willReturn(true);

        self::assertFalse($selector->matches($classReflection));
    }
}
