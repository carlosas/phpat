<?php declare(strict_types=1);

namespace Tests\PHPat\unit\selectors;

use PHPat\Selector\IsThrowable;

/**
 * @internal
 *
 * @covers \PHPat\Selector\IsThrowable
 */
class IsThrowableTest extends SelectorTestCase
{
    public function testMatchesThrowable(): void
    {
        $selector = new IsThrowable();
        $classReflection = $this->getReflectionClass(ExceptionDummyClassValid::class);

        self::assertTrue($selector->matches($classReflection));
    }

    public function testDoesNotMatchNonThrowable(): void
    {
        $selector = new IsThrowable();
        $classReflection = $this->getReflectionClass(DummyClassInvalid::class);

        self::assertFalse($selector->matches($classReflection));
    }
}
